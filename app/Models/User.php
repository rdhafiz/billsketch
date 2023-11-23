<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @param User $userInfo
     * @return array
     */
    public static function parseData(User $userInfo): array
    {
        return [
            'id' => $userInfo['id'],
            'full_name' => $userInfo['first_name'].' '.$userInfo['last_name'],
            'first_name' => $userInfo['first_name'],
            'last_name' => $userInfo['last_name'],
            'email' => $userInfo['email'],
            'phone' => $userInfo['phone'],
            'gender' => $userInfo['gender'],
            'avatar' => $userInfo['avatar'],
            'address' => $userInfo['address'],
            'city' => $userInfo['city'],
            'country' => $userInfo['country'],
        ];
    }
}
