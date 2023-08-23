<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Promotion extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['metadata' => 'json'];

    // Relationships
    // You can add relationships if needed, e.g., with User model
}
