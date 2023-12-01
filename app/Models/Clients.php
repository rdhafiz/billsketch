<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
        'logo_path',
    ];

    public function getLogoPathAttribute()
    {
        if (!empty($this->logo)) {
            return asset('storage/uploads/'.$this->logo);
        }
        return null;
    }
}
