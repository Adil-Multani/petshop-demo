<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['password', 'email_verified_at'];

    protected $casts = [
        'is_admin'     => 'boolean',
        'is_marketing' => 'boolean',
    ];

    // Relationships
    public function tokens()
    {
        return $this->hasMany(JwtToken::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

