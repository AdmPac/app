<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Order;
use App\Models\Order\OrderStatus as OrderStatus;
use App\Models\OrderItems;
use App\Models\Phone;
use App\Models\Product;
use App\Models\Product\ProductStatus as Status;
use App\Models\Product\ProductType as Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\AuthTests;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase, WithFaker, AuthTests;

    public function testCartGetSuccess(): void
    {
        $response = $this->getJson('/api/cart');
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testCartPostProductFailValidation(): void
    {
        $status = Status::create(['name' => 'Активен', 'code' => 1]);
        $type = Type::create(['name' => 'Активен', 'code' => 1]);
        $product = Product::factory()->state([
            'status_id' => $status->id,
            'type_id' => $type->id,
        ])->create();
        $response = $this->postJson('/api/cart/' . $product->id, [
            'quantity' => 12,
        ]);
        $response->assertStatus(422);
    }
    public function testCartPostProductSuccessValidation(): void
    {
        $product = Status::create(['name' => 'Активен', 'code' => 1]);
        $type = Type::create(['name' => 'Активен', 'code' => 1]);
        $product = Product::factory()->state([
            'status_id' => $product->id,
            'type_id' => $type->id,
            'limit' => 10,
        ])->create();
        $response = $this->postJson('/api/cart/' . $product->id, [
            'quantity' => 1,
        ]);
        $response->assertStatus(200);
    }

    public function testCartDeliverySuccess(): void
    {
        $authData = $this->authToken();
        OrderStatus::insert([
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
            'status_id' => OrderStatus::inRandomOrder()->value('id'),
            'phone_id' => Phone::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),
        ])->create();
        
        $response = $this->postJson('/api/cart/delivery', [
            'phone' => '+79999999999',
            'address' => 'Москва, ул. Ленина, 12345',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
    }

    public function testCartDeliveryFailValidation(): void
    {
        $token = $this->authToken();
        $response = $this->postJson('/api/cart/delivery', [
            'address' => 'Москва, ул. Ленина, 12345',
        ], [
            'Authorization' => 'Bearer ' . $token['token'],
        ]);
        $response->assertStatus(422);
    }

    public function testCartDeleteProductSuccess(): void
    {

        $authData = $this->authToken();
        $token = $authData['token'];
        $product = Status::create(['name' => 'Активен', 'code' => 1]);
        $type = Type::create(['name' => 'Активен', 'code' => 1]);
        $product = Product::factory()->state([
            'status_id' => $product->id,
            'type_id' => $type->id,
            'limit' => 10,
        ])->create();

        OrderStatus::insert([
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
            'status_id' => OrderStatus::inRandomOrder()->value('id'),
            'phone_id' => Phone::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),
        ])->create();

        $quantity = 3;
        OrderItems::create([
            'name' => $product->name,
            'description' => $product->description,
            'img' => $product->img,
            'quantity' => $quantity,
            'cost' => $product->cost * $quantity,
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);

        $response = $this->deleteJson('/api/cart/' . $product->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
    }

    public function testCartDeleteProductFail(): void
    {

        $authData = $this->authToken();
        $token = $authData['token'];
        $product = Status::create(['name' => 'Активен', 'code' => 2]);
        $type = Type::create(['name' => 'Активен', 'code' => 1]);
        $product = Product::factory()->state([
            'status_id' => $product->id,
            'type_id' => $type->id,
            'limit' => 10,
        ])->create();

        OrderStatus::insert([
            ['value' => 'Активен', 'code' => 2],
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
            'status_id' => OrderStatus::inRandomOrder()->value('id'),
            'phone_id' => Phone::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),
        ])->create();

        $quantity = 3;
        OrderItems::create([
            'name' => $product->name,
            'description' => $product->description,
            'img' => $product->img,
            'quantity' => $quantity,
            'cost' => $product->cost * $quantity,
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);

        $response = $this->deleteJson('/api/cart/' . $product->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(404);
    }

    public function testCartPatchProductSuccess(): void
    {

        $authData = $this->authToken();
        $token = $authData['token'];
        $product = Status::create(['name' => 'Активен', 'code' => 1]);
        $type = Type::create(['name' => 'Активен', 'code' => 1]);
        $product = Product::factory()->state([
            'status_id' => $product->id,
            'type_id' => $type->id,
            'limit' => 10,
        ])->create();

        OrderStatus::insert([
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
            'status_id' => OrderStatus::inRandomOrder()->value('id'),
            'phone_id' => Phone::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),
        ])->create();

        $quantity = 1;
        OrderItems::create([
            'name' => $product->name,
            'description' => $product->description,
            'img' => $product->img,
            'quantity' => $quantity,
            'cost' => $product->cost * $quantity,
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);
        

        $response = $this->patchJson('/api/cart/' . $product->id, ['quantity' => 3], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
    }

    public function testCartPatchProductFail(): void
    {

        $authData = $this->authToken();
        $token = $authData['token'];
        $product = Status::create(['name' => 'Активен', 'code' => 1]);
        $type = Type::create(['name' => 'Активен', 'code' => 1]);
        $product = Product::factory()->state([
            'status_id' => $product->id,
            'type_id' => $type->id,
            'limit' => 10,
        ])->create();

        $response = $this->patchJson('/api/cart/' . $product->id, ['quantity' => 3], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(404);
    }
}
