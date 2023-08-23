<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['metadata' => 'json'];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_uuid', 'uuid');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product', 'product_uuid', 'order_uuid')
                    ->withPivot(['quantity'])
                    ->withTimestamps();
    }
}
