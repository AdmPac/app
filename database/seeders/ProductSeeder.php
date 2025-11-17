<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product as ProductModel;
use App\Models\Product\ProductStatus as ProductStatus;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductModel::truncate();
      
        $statuses = ProductStatus::pluck('id')->toArray();

        $faker = Faker::create();

        ProductModel::insert([
            [
                'name' => 'Пицца', 
                'img' => $faker->imageUrl(), 
                'description' => $faker->text(), 
                'cost' => 1000, 
                'type_id' => 1, 
                'status_id' => $faker->randomElement($statuses), 
                'limit' => 10
            ],
            [
                'name' => 'Напиток', 
                'img' => $faker->imageUrl(), 
                'description' => $faker->text(), 
                'cost' => 1000, 
                'type_id' => 2, 
                'status_id' => $faker->randomElement($statuses), 
                'limit' => 20
            ],
        ]);
    }
}
