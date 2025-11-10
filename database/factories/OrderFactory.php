<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order\Status;
use App\Models\Phone;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusOrder = Status::pluck('id')->toArray();

        $phones = Phone::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        $addresses = Address::pluck('id')->toArray();
        
        return [
            'user_id' => fake()->randomElement($users),
            'phone_id' => fake()->randomElement($phones),
            'address_id' => fake()->randomElement($addresses),
            'status_id' => fake()->numberBetween(2, count($statusOrder)),
        ];
    }
}
