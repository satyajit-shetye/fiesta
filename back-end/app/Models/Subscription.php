<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'start_date',
        'end_date',
        'user_id'
    ];

    protected $hidden = [
        'user_id',
        'is_revoked',
        'created_at',
        'updated_at'
    ];
}
