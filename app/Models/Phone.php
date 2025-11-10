<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;
    
    protected $fillable = ['value'];

    public $timestamps = false;

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
