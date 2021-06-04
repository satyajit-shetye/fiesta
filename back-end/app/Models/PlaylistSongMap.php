<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistSongMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'playlist_id',
        'user_id'
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at'
    ];
}
