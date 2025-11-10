<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::pluck('id')->toArray();
        $product = Product::select('id', 'name', 'type_id', 'img', 'description', 'cost')->get()->toArray();
        
        return [
            'name' => fake()->randomElement(array_column($product, 'name')),
            'img' => fake()->randomElement(array_column($product, 'img')),
            'quantity' => array_column($product, 'type_id') === 1 ? fake()->numberBetween(1, 10) : fake()->numberBetween(1, 20),
            'description' => fake()->randomElement(array_column($product, 'description')),
            'cost' => fake()->randomElement(array_column($product, 'cost')),
            'product_id' => fake()->randomElement(array_column($product, 'id')),
            'order_id' => fake()->randomElement($order),
        ];
    }
}
