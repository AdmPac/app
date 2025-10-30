<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Status extends Model
{
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
