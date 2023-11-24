<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Constants\UserType;
use App\Helpers\Helpers;
use App\Models\Companies;
use App\Models\User;
use App\Repositories\CompaniesRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function get(Request $request): array|JsonResponse
    {
        try {
            $requestData = $request->all();
            $sessionUser = $requestData['session_user'];
            $userData = ProfileRepository::get($sessionUser['id']);
            if (!$userData instanceof User) {
                return response()->json(['status' => 500, 'message' => 'Cannot find user'], 200);
            }
            if ($userData['user_type'] == UserType::Company) {
                $companyData = CompaniesRepository::get($userData['company_id']);
                if (!$companyData instanceof Companies) {
                    return response()->json(['status' => 500, 'message' => 'Cannot find company'], 200);
                }
                $userData['company_info'] = $companyData;
            }
            return response()->json(['status' => 200, 'data' => $userData]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'first_name' => 'required|string',
                'last_name' => 'nullable|string',
                'email' => 'required|email',
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
            $user = User::where('email', $requestData['email'])->where('id', '!=', $requestData['session_user']['id'])->first();
            if ($user instanceof User) {
                return response()->json(['status' => 500, 'errors' => ['email' => ['Email already has been taken']]]);
            }
            $userData = [
                'first_name' => $requestData['first_name'],
                'last_name' => $requestData['last_name'] ?? null,
                'email' => $requestData['email'],
                'phone' => $requestData['phone'] ?? null,
                'gender' => $requestData['gender'] ?? null,
                'address' => $requestData['address'] ?? null,
                'city' => $requestData['city'] ?? null,
                'country' => $requestData['country'] ?? null,
                'user_type' => $requestData['user_type'],
            ];
            if ($request->file('avatar')) {
                $userData['avatar'] = Helpers::fileUpload($request->file('avatar'));
            }
            $user = User::find($requestData['session_user']['id']);
            if (!$user instanceof User) {
                return response()->json(['status' => 500, 'message' => 'Cannot find user'], 200);
            }
            $userInfo = ProfileRepository::update($user, $userData);
            if (!$userInfo instanceof User) {
                return response()->json(['status' => 500, 'message' => 'Cannot update profile'], 200);
            }
            if ($userInfo['user_type'] == UserType::Company) {
                $companyData = [
                    'company_name' => $requestData['company_name'],
                    'company_size' => $requestData['company_size'],
                    'company_address' => $requestData['company_address'],
                    'company_city' => $requestData['company_city'],
                    'company_country' => $requestData['company_country'],
                ];
                if ($request->file('company_logo')) {
                    $companyData['company_logo'] = Helpers::fileUpload($request->file('company_logo'));
                }
                $company = Companies::find($userInfo['company_id']);
                if (!$company instanceof Companies) {
                    return response()->json(['status' => 500, 'message' => 'Cannot find company'], 200);
                }
                $companyInfo = CompaniesRepository::update($company, $companyData);
                if (!$companyInfo instanceof Companies) {
                    return response()->json(['status' => 500, 'message' => 'Cannot update company'], 200);
                }
            }
            Helpers::saveUserActivity($userInfo['id'],UserLogType::Update_profile);
            return response()->json(['status' => 200, 'message' => 'Profile updated successfully']);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'old_password' => 'required|string',
                'password' => 'required|confirmed',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $user = User::find($requestData['session_user']['id']);
            if (!$user instanceof User) {
                return response()->json(['status' => 500, 'message' => 'Cannot find user'], 200);
            }
            if (!Hash::check($requestData['old_password'], $user->password)) {
                return response()->json(['status' => 500, 'errors' => ['old_password' => ['Current password does not match']]], 200);
            }
            $user->password = bcrypt($requestData['password']);
            if (!$user->save()) {
                return response()->json(['status' => 500, 'message' => 'Cannot update password'], 200);
            }
            Helpers::saveUserActivity($user['id'],UserLogType::Change_password);
            return response()->json(['status' => 200, 'message' => 'Password updated successfully']);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }
}
