<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Type extends Model
{
    protected $table = "product_types";
    protected $fillable = [
        'name',
        'code'
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
