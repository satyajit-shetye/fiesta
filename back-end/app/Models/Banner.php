<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_url',
        'thumbnail_url'
    ];

    protected $hidden = [
        'is_deleted',
        'is_active',
        'created_at',
        'updated_at'
    ];
}
