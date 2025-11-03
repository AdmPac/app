<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product\Status;
use App\Models\Product\Type;
use App\Models\OrderItems;

class Product extends Model
{
    use HasFactory;
    
    public $fillable = [
        'name',
        'description',
        'cost',
        'type_id',
        'status_id',
        'limit',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function productItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
