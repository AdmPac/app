<?php

namespace App\Models;

use App\Models\Order\OrderStatus as Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    public $table = 'orders';
    public $fillable = [
        'user_id',
        'address_id',
        'phone_id',
        'status_id',
    ];

    public $casts = [
        'user_id' => 'integer',
        'address_id' => 'integer',
        'phone_id' => 'integer',
        'status_id' => 'integer',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function phone(): BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'order_items',
            'order_id',
            'product_id'
        )->withPivot(
            'id',
            'quantity',
            'cost', 
            'name', 
            'img', 
            'description'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            OrderItems::class,
        );
    }
}
