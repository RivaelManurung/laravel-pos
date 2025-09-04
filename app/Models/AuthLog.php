<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    protected $table = 'auth_logs';

    protected $fillable = [
        'user_id',
        'event',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
