<?php

namespace App\Http\Controllers;

use App\Constants\UserType;
use App\Models\Companies;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Repositories\CompaniesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $userInfo = User::where('email', $requestData['email'])->first();
            if (!$userInfo instanceof User) {
                return response()->json(['status' => 500, 'errors' => ['email' => ['Invalid credential! Please try again']]], 200);
            }
            if ($userInfo->activation_code != null) {
                AuthRepository::sendVerificationEmail($userInfo);
                return response()->json(['status' => 300, 'message' => 'Your account is not verified yet. we send a mail again. Please verify your account'], 200);
            } else {
                if (Hash::check($requestData['password'], $userInfo->password)) {
                    $access_token = $userInfo->createToken('authToken')->accessToken;
                    return response()->json(['status' => 200, 'access_token' => $access_token, 'user' => User::parseData($userInfo)]);
                }
            }
            return response()->json(['status' => 500, 'errors' => ['password' => ['Password is not correct! Please try again.']]], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function socialLogin(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'first_name' => 'required|string',
                'last_name' => 'nullable|string',
                'social_provider' => 'required|string',
                'social_provider_id' => 'required|integer',
                'email' => 'nullable|email',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $email = $requestData['email'] ?? $requestData['social_provider_id'].'@'.$requestData['social_provider'].'.com';
            $userInfo = User::where('email', $email)
                ->where('social_provider', $requestData['social_provider'])
                ->where('social_provider_id', $requestData['social_provider_id'])
                ->first();

            if (!$userInfo instanceof User) {
                $password = rand(10000, 999999);
                $userData = [
                    'first_name' => $requestData['first_name'],
                    'last_name' => $requestData['last_name'] ?? null,
                    'social_provider' => $requestData['social_provider'],
                    'social_provider_id' => $requestData['social_provider_id'],
                    'email' => $email,
                    'password' => bcrypt($password),
                ];
                $userInfo = AuthRepository::save($userData);
                if (!$userInfo instanceof User) {
                    return response()->json(['status' => 500, 'message' => 'Cannot social login'], 200);
                }
            }
            $access_token = $userInfo->createToken('authToken')->accessToken;
            return response()->json(['status' => 200, 'access_token' => $access_token, 'user' => User::parseData($userInfo)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function registration(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'first_name' => 'required|string',
                'last_name' => 'nullable|string',
                'email' => 'required|email',
                'password' => 'required|confirmed',
                'user_type' => 'required|integer',
                'company_name' =>'required_if:user_type,2',
                'company_size' =>'required_if:user_type,2',
                'company_address' =>'required_if:user_type,2',
                'company_city' =>'required_if:user_type,2',
                'company_country' =>'required_if:user_type,2',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $activationCode = rand(100000, 999999);
            $userData = [
                'first_name' => $requestData['first_name'],
                'last_name' => $requestData['last_name'] ?? null,
                'email' => $requestData['email'],
                'password' => bcrypt($requestData['password']),
                'phone' => $requestData['phone'] ?? null,
                'gender' => $requestData['gender'] ?? null,
                'address' => $requestData['address'] ?? null,
                'city' => $requestData['city'] ?? null,
                'country' => $requestData['country'] ?? null,
                'user_type' => $requestData['user_type'],
                'activation_code' => $activationCode,
            ];
            $userInfo = AuthRepository::save($userData);
            if (!$userInfo instanceof User) {
                return response()->json(['status' => 500, 'message' => 'Cannot register user'], 200);
            }
            if ($userInfo['user_type'] == UserType::Company) {
                $companyData = [
                    'company_name' => $requestData['company_name'],
                    'company_size' => $requestData['company_size'],
                    'company_address' => $requestData['company_address'],
                    'company_city' => $requestData['company_city'],
                    'company_country' => $requestData['company_country'],
                    'user_id' => $userInfo['id'],
                ];
                $companyInfo = CompaniesRepository::save($companyData);
                if (!$companyInfo instanceof Companies) {
                    DB::rollBack();
                    return response()->json(['status' => 500, 'message' => 'Cannot save company'], 200);
                }
                $userInfo->company_id = $companyInfo['id'];
                if (!$userInfo->save()) {
                    DB::rollBack();
                    return response()->json(['status' => 500, 'message' => 'Cannot register user'], 200);
                }
            }
            AuthRepository::sendVerificationEmail($userInfo);
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'A verification mail has been sent to your email, Please verify the email to login'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyAccount(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'code' => 'required|string',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $userInfo = User::where('email', $requestData['email'])->where('activation_code', $requestData['code'])->first();
            if (!$userInfo instanceof User) {
                return response()->json(['status' => 500, 'message' => 'Cannot find user'], 200);
            }
            $userInfo->activation_code = null;
            if (!$userInfo->save()) {
                return response()->json(['status' => 500, 'message' => 'Cannot verify user, Please try again'], 200);
            }
            $access_token = $userInfo->createToken('authToken')->accessToken;
            return response()->json(['status' => 200, 'access_token' => $access_token, 'user' => User::parseData($userInfo)]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $userInfo = User::where('email', $requestData['email'])->first();
            if (!$userInfo instanceof User) {
                return response()->json(['status' => 500, 'errors' => ['email' => ['Invalid Email']]], 200);
            }
            $resetCode = rand(100000, 999999);
            $userInfo->reset_code = $resetCode;
            if (!$userInfo->save()) {
                return response()->json(['status' => 500, 'message' => 'Cannot set reset code'], 200);
            }
            Mail::send('email.reset_code', ['userInfo' => $userInfo], function ($message) use ($userInfo) {
                $message->to($userInfo['email'], $userInfo['first_name'].' '.$userInfo['last_name'] ?? '')->subject(env('APP_NAME') . ': Password Reset code');
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
            return response()->json(['status' => 200, 'message' => 'A reset code has been sent to your email'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'email' => 'required|email',
                'reset_code' => 'required|string',
                'password' => 'required|confirmed',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $userInfo = User::where('email', $requestData['email'])->where('reset_code', $requestData['reset_code'])->first();
            if (!$userInfo instanceof User) {
                return response()->json(['status' => 500, 'errors' => ['reset_code' => ['Invalid reset code']]], 200);
            }
            $userInfo->reset_code = null;
            $userInfo->password = bcrypt($requestData['password']);
            if (!$userInfo->save()) {
                return response()->json(['status' => 500, 'message' => 'Cannot reset password'], 200);
            }
            return response()->json(['status'=> 200, 'message' => 'Password reset successful']);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }
}
