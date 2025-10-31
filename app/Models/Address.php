<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
