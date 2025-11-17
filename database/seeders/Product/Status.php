<?php

namespace Database\Seeders\Product;

use App\Models\Product\ProductStatus as ProductStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Status extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // игнорируем проверку внешних ключей
        ProductStatus::truncate();
        ProductStatus::insert([
            ['name' => 'Активен', 'code' => 1],
            ['name' => 'Не активен', 'code' => 2],
        ]);
    }
}
