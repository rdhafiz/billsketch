<?php

namespace App\Repositories;

use App\Constants\UserType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class AuthRepository
{
    /**
     * Saves user data to the database.
     *
     * This method creates a new User model instance, populates it with the provided
     * user data, and saves it to the database. It returns an array containing the
     * user data in case of success or an error message in case of failure.
     *
     * @param array $userData An array containing user data to be saved.
     *
     * @return array|User An array containing user data in case of success or an error message in case of failure.
     */
    public static function save(array $userData): array|User
    {
        // Creating a new instance of the User model
        $userModel = new User();

        // Populating the User model with the provided user data
        $userModel->company_id = $userData['company_id'] ?? null;
        $userModel->first_name = $userData['first_name'];
        $userModel->last_name = $userData['last_name'] ?? null;
        $userModel->email = $userData['email'];
        $userModel->password = $userData['password'];
        $userModel->phone = $userData['phone'] ?? null;
        $userModel->gender = $userData['gender'] ?? null;
        $userModel->avatar = $userData['avatar'] ?? null;
        $userModel->address = $userData['address'] ?? null;
        $userModel->city = $userData['city'] ?? null;
        $userModel->country = $userData['country'] ?? null;
        $userModel->activation_code = $userData['activation_code'] ?? null;
        $userModel->reset_code = $userData['reset_code'] ?? null;
        $userModel->social_provider = $userData['social_provider'] ?? null;
        $userModel->social_provider_id = $userData['social_provider_id'] ?? null;
        $userModel->user_type = $userData['user_type'] ?? UserType::Individual;

        // Attempting to save the User model to the database
        if (!$userModel->save()) {
            // Returning an error message if the save operation fails
            return ['message' => 'Cannot save user'];
        }

        // Returning the User model instance in case of success
        return $userModel;
    }

    /**
     * Sends a verification email to the user.
     *
     * This method constructs a verification link for the user, composes an email using
     * the provided user information, and sends it to the user's email address. It returns
     * a boolean indicating the success of the email sending operation.
     *
     * @param User $userInfo The User model instance containing user information.
     *
     * @return bool A boolean indicating the success of the email sending operation.
     */
    public static function sendVerificationEmail(User $userInfo): bool
    {
        // Constructing the verification link using the application URL
        $userInfo['link'] = env('APP_URL') . '/activate';

        // Composing and sending the verification email
        Mail::send('email.verification', ['userInfo' => $userInfo], function ($message) use ($userInfo) {
            // Setting the recipient email address and email subject
            $message->to($userInfo['email'], $userInfo['first_name'] . ' ' . $userInfo['last_name'] ?? '')->subject(env('APP_NAME') . ': Verification Link');

            // Setting the sender email address and name
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        // Returning true to indicate the success of the email sending operation
        return true;
    }

}
