<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order\OrderStatus as Status;
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
        $statusOrder = Status::inRandomOrder()->value('id');
        $phones = Phone::inRandomOrder()->value('id');
        $users = User::inRandomOrder()->value('id');
        $addresses = Address::inRandomOrder()->value('id');
        
        return [
            'user_id' => $users,
            'phone_id' => $phones,
            'address_id' => $addresses,
            'status_id' => $statusOrder,
        ];
    }
}
