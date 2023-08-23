<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['restrictions' => 'json', 'permissions' => 'json'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
