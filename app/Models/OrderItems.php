<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItems extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'img',
        'quantity',
        'cost',
        'product_id',
        'order_id',
    ];

    public $casts = [
        'quantity' => 'integer',
        'cost' => 'float'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
