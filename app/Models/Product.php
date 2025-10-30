<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product\Status;
use App\Models\Product\Type;

class Product extends Model
{
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
