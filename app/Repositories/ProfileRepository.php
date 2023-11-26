<?php

namespace App\Repositories;

use App\Models\User;

class ProfileRepository
{
    /**
     * @param integer $userId
     * @return array|User
     */
    public static function get(int $userId): array|User
    {
        $userData = User::select('id', 'company_id', 'first_name', 'last_name', 'email', 'phone', 'gender', 'avatar', 'user_type', 'address', 'city', 'country')
            ->where('id', $userId)
            ->first();
        if (!$userData instanceof User) {
            return ['message' => 'Cannot find user'];
        }
        return $userData;
    }
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
        if (!empty($userData['avatar'])) {
            $user->avatar = $userData['avatar'];
        }
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
