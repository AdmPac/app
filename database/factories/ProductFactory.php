<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product\ProductType as Type;
use App\Models\Product\ProductStatus as Status;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'img' => fake()->imageUrl(),
            'description' => fake()->text(),
            'cost' => fake()->randomFloat(2, 1, 1000),
            'type_id' => Type::inRandomOrder()->value('id'),
            'status_id' => Status::inRandomOrder()->value('id'),
        ];
    }
}
