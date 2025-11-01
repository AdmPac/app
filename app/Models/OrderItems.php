<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected function product()
    {
        return $this->belongsToMany(Product::class);
    }
    protected function order()
    {
        return $this->belongsToMany(Order::class);
    }
}
