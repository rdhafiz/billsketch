<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserActivityLogs extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'created_at_formatted',
        'updated_at_formatted',
    ];


    public function getCreatedAtFormattedAttribute()
    {
        if (!empty($this->created_at)) {
            return $this->created_at->format('d/m/Y');
        }
        return null;
    }
    public function getUpdatedAtFormattedAttribute()
    {
        if (!empty($this->updated_at)) {
            return $this->updated_at->format('d/m/Y');
        }
        return null;
    }
}
