<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    protected $table = "product_types";
    protected $fillable = [
        'name',
        'code'
    ];
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}


