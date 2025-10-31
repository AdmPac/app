<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

use App\Models\Order;

class Status extends Model
{
    protected $table = "statuses_orders";

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
