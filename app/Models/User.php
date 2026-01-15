<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',        // Add this
        'last_login',    // Add this
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime', // Add this
        ];
    }
    // Add status attribute with default value
    protected $attributes = [
        'status' => 'Activated',
    ];

    public function getFormattedLastLoginAttribute()
    {
        if (!$this->last_login) {
            return 'Never logged in';
        }
        
        return Carbon::parse($this->last_login)
            ->setTimezone(config('app.timezone', 'Asia/Manila'))
            ->format('Y-m-d H:i');
    }

}