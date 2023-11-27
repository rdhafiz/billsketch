<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employees extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
        'avatar_path'
    ];

    public function getAvatarPathAttribute()
    {
        if (!empty($this->avatar)) {
            return asset('storage/uploads/'.$this->avatar);
        }
        return null;
    }
}
