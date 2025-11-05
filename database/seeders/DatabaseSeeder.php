<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Product\Status;
use Database\Seeders\Product\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'mytest@example.com',
            'password' => 'test'
        ]);
        
        $this->call([
            Type::class,
            Status::class,
            ProductSeeder::class
        ]);
    }
}
