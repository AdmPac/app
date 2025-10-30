<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Type extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
