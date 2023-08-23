<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['products' => 'json', 'address' => 'json'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_uuid', 'product_uuid')
                    ->withPivot(['quantity'])
                    ->withTimestamps();
    }
}
