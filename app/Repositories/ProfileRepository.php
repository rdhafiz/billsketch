<?php

namespace App\Repositories;

use App\Models\User;

class ProfileRepository
{
    /**
     * @param User $user
     * @param array $userData
     * @return array|User
     */
    public static function update(User $user, array $userData): array|User
    {
        $user->first_name = $userData['first_name'];
        $user->last_name = $userData['last_name'] ?? null;
        $user->email = $userData['email'];
        $user->phone = $userData['phone'] ?? null;
        $user->gender = $userData['gender'] ?? null;
        $user->avatar = $userData['avatar'] ?? null;
        $user->address = $userData['address'] ?? null;
        $user->city = $userData['city'] ?? null;
        $user->country = $userData['country'] ?? null;
        $user->user_type = $userData['user_type'];
        if (!$user->save()) {
            return ['message' => 'Cannot update user'];
        }
        return $user;
    }
}
