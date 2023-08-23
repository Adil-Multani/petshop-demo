<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Payment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['details' => 'json'];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'payment_id', 'id');
    }
}
