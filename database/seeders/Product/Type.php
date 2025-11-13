<?php

namespace Database\Seeders\Product;

use App\Models\Product\Type as ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Type extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProductType::truncate();
        ProductType::insert([
            ['name' => 'Напиток', 'code' => 1],
            ['name' => 'Пицца', 'code' => 2],
        ]);
    }
}
