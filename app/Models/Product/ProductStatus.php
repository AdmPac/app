<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductStatus extends Model
{
    protected $table = "product_statuses";
    protected $fillable = [
        'name',
        'code'
    ];
    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}


