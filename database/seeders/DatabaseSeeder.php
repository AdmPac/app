<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\Order\OrderStatus as OrderStatus;
use App\Models\OrderItems;
use App\Models\Phone;
use App\Models\User;
use Database\Seeders\Product\Status;
use Database\Seeders\Product\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();
        User::factory(5)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'test',
        ]);
        
        $this->call([
            Type::class,
            Status::class,
            ProductSeeder::class
        ]);
        Phone::truncate();
        Phone::factory(10)->create();
        
        Address::truncate();
        Address::factory(10)->create();
        
        $orderStatus = [
            ['value' => 'Активен', 'code' => 1,],
            ['value' => 'В обработке', 'code' => 2],
            ['value' => 'В доставке', 'code' => 3],
            ['value' => 'Завершен', 'code' => 4],
        ];
        OrderStatus::truncate();
        OrderStatus::insert($orderStatus);
        
        Order::truncate();
        Order::factory(10)->create();

        OrderItems::truncate();
        OrderItems::factory(15)->create();
    }
}
