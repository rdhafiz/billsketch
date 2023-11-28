<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
        'icon_path'
    ];

    public function getIconPathAttribute()
    {
        if (!empty($this->icon)) {
            return asset('storage/uploads/'.$this->icon);
        }
        return null;
    }
}
