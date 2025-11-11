<?php

namespace App\Models;

use App\Models\Order\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function product()
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

    public function item()
    {
        return $this->hasMany(
            OrderItems::class,
        );
    }
}
