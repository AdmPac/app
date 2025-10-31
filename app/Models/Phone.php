<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
