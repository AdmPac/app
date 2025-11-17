<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderStatus extends Model
{
    protected $table = "status_orders";

    public $timestamps = false;

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}


