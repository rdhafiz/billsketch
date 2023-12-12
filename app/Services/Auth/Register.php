<?php

namespace App\Services\Auth;

use App\Constants\UserLogType;
use App\Constants\UserType;
use App\Helpers\Helpers;
use App\Models\Companies;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Repositories\CompaniesRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Register
{
    /**
     * Handles user registration functionality.
     *
     * This method initiates a database transaction and validates and processes user
     * registration requests, creating a new user and associated company data if applicable.
     * It returns an array containing the status and relevant data or error messages.
     *
     * @param mixed $request The request object or array containing user registration input.
     *
     * @return array An array containing the status and relevant data or error messages.
     */
    public static function make($request): array
    {
        try {
            // Starting a database transaction
            DB::beginTransaction();

            // Extracting all data from the request
            $requestData = $request->all();

            // Validating the request data using Laravel Validator
            $validator = Validator::make($requestData, [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed',
                'user_type' => 'required|integer',
                'company_name' => 'required_if:user_type,2',
                'company_size' => 'nullable',
                'company_address' => 'required_if:user_type,2',
                'company_city' => 'required_if:user_type,2',
                'company_country' => 'required_if:user_type,2',
            ], [
                'company_name' => 'Company name is required',
                'company_address' => 'Address is required',
                'company_city' => 'City is required',
                'company_country' => 'Country is required',
            ]);

            // Checking if validation fails
            if ($validator->fails()) {
                // Returning validation errors if any
                return ['status' => 500, 'errors' => $validator->errors()];
            }

            // Generating a random activation code for email verification
            $activationCode = rand(100000, 999999);

            // Creating user data for the new user
            $userData = [
                'first_name' => $requestData['first_name'],
                'last_name' => $requestData['last_name'],
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

            // Saving the new user through AuthRepository
            $userInfo = AuthRepository::save($userData);

            // Returning an error response if the user could not be saved
            if (!$userInfo instanceof User) {
                return ['status' => 500, 'message' => 'Cannot register user'];
            }

            // Handling additional steps for company-type users
            if ($userInfo['user_type'] == UserType::Company) {
                // Creating company data for the new company
                $companyData = [
                    'company_name' => $requestData['company_name'],
                    'company_size' => $requestData['company_size'] ?? 0,
                    'company_address' => $requestData['company_address'],
                    'company_city' => $requestData['company_city'],
                    'company_country' => $requestData['company_country'],
                    'user_id' => $userInfo['id'],
                ];

                // Saving the new company through CompaniesRepository
                $companyInfo = CompaniesRepository::save($companyData);

                // Rolling back the transaction and returning an error response if the company could not be saved
                if (!$companyInfo instanceof Companies) {
                    DB::rollBack();
                    return ['status' => 500, 'message' => 'Cannot save company'];
                }

                // Updating the user with the associated company ID
                $userInfo->company_id = $companyInfo['id'];

                // Rolling back the transaction and returning an error response if the user could not be updated
                if (!$userInfo->save()) {
                    DB::rollBack();
                    return ['status' => 500, 'message' => 'Cannot register user'];
                }
            }

            // Sending a verification email to the user
            AuthRepository::sendVerificationEmail($userInfo);

            // Committing the transaction
            DB::commit();

            // Logging user activity for successful registration
            Helpers::saveUserActivity($userInfo['id'], UserLogType::Register, $userInfo['first_name'] . ' ' . $userInfo['first_name'] . ' registered');

            // Returning success response with a message for email verification
            return ['status' => 200, 'message' => 'A verification mail has been sent to your email, Please verify the email to login'];
        } catch (\Exception $exception) {
            // Rolling back the transaction and returning an error response for exceptions
            DB::rollBack();
            return ['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()];
        }
    }

    /**
     * Handles user account verification functionality.
     *
     * This method validates and processes user account verification requests,
     * updating the user's activation status and returning relevant data.
     *
     * @param mixed $request The request object or array containing verification input.
     *
     * @return array An array containing the status and relevant data or error messages.
     */
    public static function verifyAccount($request): array
    {
        try {
            // Extracting all data from the request
            $requestData = $request->all();

            // Validating the request data using Laravel Validator
            $validator = Validator::make($requestData, [
                'code' => 'required|string',
                'email' => 'required|email',
            ]);

            // Checking if validation fails
            if ($validator->fails()) {
                // Returning validation errors if any
                return ['status' => 500, 'errors' => $validator->errors()];
            }

            // Retrieving user information based on email and activation code
            $userInfo = User::where('email', $requestData['email'])->where('activation_code', $requestData['code'])->first();

            // Returning an error response if the user is not found
            if (!$userInfo instanceof User) {
                return ['status' => 500, 'message' => 'Cannot find user'];
            }

            // Updating user activation code to null to mark the account as verified
            $userInfo->activation_code = null;

            // Saving the updated user information
            if (!$userInfo->save()) {
                return ['status' => 500, 'message' => 'Cannot verify user, Please try again'];
            }

            // Generating an access token for the verified user
            $access_token = $userInfo->createToken('authToken')->accessToken;

            // Logging user activity for account activation
            Helpers::saveUserActivity($userInfo['id'], UserLogType::Account_Activation, $userInfo['first_name'] . ' ' . $userInfo['first_name'] . ' verified his account');

            // Returning success response with access token and user data
            return ['status' => 200, 'access_token' => $access_token, 'user' => User::parseData($userInfo)];
        } catch (\Exception $exception) {
            // Returning an error response for exceptions
            return ['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()];
        }
    }

}
