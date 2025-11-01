<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product as ProductModel;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductModel::factory()->count(3)->create();
        ProductModel::insert([
            ['name' => 'Пицца', 'img' => 'https://img.freepik.com/free-psd/top-view-delicious-pizza_23-2151868964.jpg?semt=ais_hybrid&w=740&q=80', 'description' => 'Просто пицца', 'cost' => 1000, 'type_id' => 1, 'status_id' => 1, 'limit' => 10],
            ['name' => 'Напиток', 'img' => 'https://media.istockphoto.com/id/458120031/ru/%D1%84%D0%BE%D1%82%D0%BE/%D0%B8%D0%B7%D0%BE%D0%BB%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%BD%D1%8B%D0%B5-%D0%BE%D1%85%D0%BB%D0%B0%D0%B6%D0%B4%D0%B5%D0%BD%D0%BD%D1%8B%D0%B5-%D0%BA%D0%BE%D0%BA%D0%B0-%D0%BA%D0%BE%D0%BB%D0%B0.jpg?s=612x612&w=0&k=20&c=dcdDHYdEXpQc4fP-hOocOzET1qqOdDZr5g0dYc1VTJM=', 'description' => 'Просто напиток', 'cost' => 1000, 'type_id' => 1, 'status_id' => 1, 'limit' => 20],
        ]);
    }
}
