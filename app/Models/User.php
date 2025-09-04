<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean'
    ];

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_CASHIER = 'cashier';
    const ROLE_MANAGER = 'manager';

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_CASHIER => 'Kasir',
            self::ROLE_MANAGER => 'Manager'
        ];
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCashier()
    {
        return $this->role === self::ROLE_CASHIER;
    }

    public function isManager()
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}