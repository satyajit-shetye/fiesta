<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardPlaylist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dashboard_category_id',
        'is_paid',
        'is_deleted',
        'is_active',
        'user_id'
    ];

    protected $hidden = [
        'is_deleted',
        'is_active',
        'created_at',
        'updated_at'
    ];
}