<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Type extends Model
{
    protected $table = "product_types";
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
