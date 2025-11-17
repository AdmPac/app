<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductStatus extends Model
{
    protected $table = "product_statuses";
    protected $fillable = [
        'name',
        'code'
    ];
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}


