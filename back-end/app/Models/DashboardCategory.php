<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_deleted',
        'is_active'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function playlists()
    {
        return $this->hasMany(DashboardPlaylist::class, 'dashboard_category_id', 'id');
    }
}