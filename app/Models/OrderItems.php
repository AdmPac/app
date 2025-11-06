<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItems extends Model
{
    use HasFactory;
    
    protected function product()
    {
        return $this->belongsToMany(Product::class);
    }
    protected function order()
    {
        return $this->belongsToMany(Order::class);
    }
}
