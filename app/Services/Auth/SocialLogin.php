<?php

namespace App\Services\Auth;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Validator;

class SocialLogin
{
    /**
     * Handles social login functionality.
     *
     * This method validates and processes social login requests, creating a new user
     * or retrieving an existing one based on social provider information. It returns
     * an array containing the status and relevant data or error messages.
     *
     * @param mixed $request The request object or array containing user input.
     *
     * @return array An array containing the status and relevant data or error messages.
     */
    public static function make($request): array
    {
        try {
            // Extracting all data from the request
            $requestData = $request->all();

            // Validating the request data using Laravel Validator
            $validator = Validator::make($requestData, [
                'first_name' => 'required|string',
                'last_name' => 'nullable|string',
                'social_provider' => 'required|string',
                'social_provider_id' => 'required|integer',
                'email' => 'nullable|email',
            ]);

            // Checking if validation fails
            if ($validator->fails()) {
                // Returning validation errors if any
                return ['status' => 500, 'errors' => $validator->errors()];
            }

            // Deriving email if not provided in the request
            $email = $requestData['email'] ?? $requestData['social_provider_id'] . '@' . $requestData['social_provider'] . '.com';

            // Retrieving user information based on social provider details
            $userInfo = User::where('email', $email)
                ->where('social_provider', $requestData['social_provider'])
                ->where('social_provider_id', $requestData['social_provider_id'])
                ->first();

            // Creating a new user if not found
            if (!$userInfo instanceof User) {
                // Generating a random password for the new user
                $password = rand(10000, 999999);

                // Creating user data for the new user
                $userData = [
                    'first_name' => $requestData['first_name'],
                    'last_name' => $requestData['last_name'] ?? null,
                    'social_provider' => $requestData['social_provider'],
                    'social_provider_id' => $requestData['social_provider_id'],
                    'email' => $email,
                    'password' => bcrypt($password),
                ];

                // Saving the new user through AuthRepository
                $userInfo = AuthRepository::save($userData);

                // Returning an error response if the user could not be saved
                if (!$userInfo instanceof User) {
                    return ['status' => 500, 'message' => 'Cannot social login'];
                }
            }

            // Generating and returning an access token upon successful social login
            $access_token = $userInfo->createToken('authToken')->accessToken;

            // Logging user activity based on the social provider
            if ($requestData['social_provider'] == 'facebook') {
                Helpers::saveUserActivity($userInfo['id'], UserLogType::SocialLoginFb, $userInfo['first_name'] . ' ' . $userInfo['first_name'] . ' logged in via facebook');
            } elseif ($requestData['social_provider'] == 'google') {
                Helpers::saveUserActivity($userInfo['id'], UserLogType::SocialLoginGl, $userInfo['first_name'] . ' ' . $userInfo['first_name'] . ' logged in via google');
            }

            // Returning success response with access token and user data
            return ['status' => 200, 'access_token' => $access_token, 'user' => User::parseData($userInfo)];
        } catch (\Exception $exception) {
            // Handling exceptions and returning an error response
            return ['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()];
        }
    }

}
