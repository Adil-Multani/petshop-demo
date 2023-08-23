<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'is_admin' => 'boolean',
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

