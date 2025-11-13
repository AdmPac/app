<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Order;
use App\Models\Order\Status;
use App\Models\Phone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\AuthTests;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use AuthTests, RefreshDatabase, WithFaker;

    public function testOrdersGetAllSuccess(): void
    {
        $token = $this->authToken()['token'];
        $response = $this->getJson('/api/orders',[
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
    }

    public function testOrdersGetAllFail(): void
    {
        $response = $this->getJson('/api/orders');
        $response->assertStatus(401);
    }

    public function testOrdersPatchStatusNotAdmin(): void
    {
        $authData = $this->authToken();
        Status::insert([
            ['value' => 'Активен', 'code' => 1],
        ]);
        Phone::insert([
            ['value' => '+79999999999'],
            ['value' => '+79999999998'],
        ]);
        Address::insert([
            ['value' => 'Москва, ул. Ленина, 1'],
            ['value' => 'Москва, ул. Пушкина, 2'],
        ]);
        $token = $authData['token'];
        $user = $authData['user'];
        $order = Order::factory()->state([
            'user_id' => $user->id,
            'status_id' => Status::inRandomOrder()->value('id'),
            'phone_id' => Phone::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),
        ])->create();

        $response = $this->patchJson('/api/orders', [
            'status' => 2,
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(405);
    }

    public function testOrdersGetByIdUnauthorized(): void
    {
        $authData = $this->authToken();
        Status::insert([
            ['value' => 'Активен', 'code' => 1],
            ['value' => 'Не активен', 'code' => 2],
        ]);
        Phone::insert([
            ['value' => '+79999999999'],
            ['value' => '+79999999998'],
        ]);
        Address::insert([
            ['value' => 'Москва, ул. Ленина, 1'],
            ['value' => 'Москва, ул. Пушкина, 2'],
        ]);
        $token = $authData['token'];
        $user = $authData['user'];
        $order = Order::factory()->state([
            'user_id' => $user->id,
            'status_id' => Status::inRandomOrder()->value('id'),
            'phone_id' => Phone::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),
        ])->create();

        $response = $this->getJson('/api/orders/' . $user->id);
        $response->assertStatus(401);
    }

    public function testOrdersGetByIdSuccess(): void
    {
        $authData = $this->authToken(true);
        Status::insert([
            ['value' => 'Активен', 'code' => 1],
            ['value' => 'Не активен', 'code' => 2],
        ]);
        Phone::insert([
            ['value' => '+79999999999'],
            ['value' => '+79999999998'],
        ]);
        Address::insert([
            ['value' => 'Москва, ул. Ленина, 1'],
            ['value' => 'Москва, ул. Пушкина, 2'],
        ]);
        $token = $authData['token'];
        $user = $authData['user'];
        $order = Order::factory()->state([
            'user_id' => $user->id,
            'status_id' => Status::inRandomOrder()->value('id'),
            'phone_id' => Phone::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),
        ])->create();
        $response = $this->getJson('/api/orders/' . $user->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
    }
}
