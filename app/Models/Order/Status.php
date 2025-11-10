<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

use App\Models\Order;

class Status extends Model
{
    protected $table = "status_orders";

    public $timestamps = false;

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
