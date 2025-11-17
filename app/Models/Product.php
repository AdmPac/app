<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product\ProductStatus as Status;
use App\Models\Product\ProductType as Type;

class Product extends Model
{
    use HasFactory;
    
    public $fillable = [
        'name',
        'img',
        'description',
        'cost',
        'type_id',
        'status_id',
        'limit',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function order()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
