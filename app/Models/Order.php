<?php

namespace App\Models;

use App\Models\Order\Status;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
}
