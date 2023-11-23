<?php

namespace App\Repositories;

use App\Constants\UserType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class AuthRepository
{
    /**
     * @param array $userData
     * @return array|User
     */
    public static function save(array $userData): array|User
    {
        $userModel = new User();
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
        if (!$userModel->save()) {
            return ['message' => 'Cannot save user'];
        }
        return $userModel;
    }

    /**
     * @param User $userInfo
     * @return true
     */
    public static function sendVerificationEmail(User $userInfo): bool
    {
        $userInfo['link'] = env('APP_URL').'/activate';
        Mail::send('email.verification', ['userInfo' => $userInfo], function ($message) use ($userInfo) {
            $message->to($userInfo['email'], $userInfo['first_name'].' '.$userInfo['last_name'] ?? '')->subject(env('APP_NAME') . ': Verification Link');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
        return true;
    }
}
