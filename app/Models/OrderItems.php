<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
