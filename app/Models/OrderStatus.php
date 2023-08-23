<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $guarded = ['id'];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status_id', 'id');
    }
}
