<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dashboard_video_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}