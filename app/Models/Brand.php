<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Brand extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_uuid', 'uuid');
    }
}