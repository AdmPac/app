<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Product\Status;
use App\Models\Product\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\AuthTests;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use AuthTests, RefreshDatabase, WithFaker;

    const PRODUCT_SUCCESS = [
        "name" => "Пицца 33см",
        "img" => "https://google.com",
        "description" => "Пицца 33см кусна",
        "cost" => "213",
        "limit" => "3"
    ];

    const PRODUCT_FAIL = [
        "status_id" => "1",
        "limit" => "3"
    ];

    protected $statusCodeActive;
    protected $statusType;
    protected $productSuccess;

    protected function setUp(): void
    {
        parent::setUp();
        
        $statusCode1 = Status::firstOrCreate(['name' => 'status_1', 'code' => 1]);
        $statusCode2 = Status::firstOrCreate(['name' => 'status_2', 'code' => 2]);
        $typeCode1 = Type::firstOrCreate(['name' => 'type_1', 'code' => 1]);
        
        $this->statusCodeActive = $statusCode1->id;
        $this->statusType = $typeCode1->id;
        $this->productSuccess = array_merge(self::PRODUCT_SUCCESS, ['status_id' => $this->statusCodeActive, 'type_id' => $this->statusType]);
    }
    
    public function testProductDeleteFail(): void
    {
        $product = Product::factory()->create();
        $response = $this->deleteJson('/api/products/' . $product->id);
        $response->assertStatus(401);
    }

    public function testProductIdSuccess(): void
    {
        $product = Product::factory()->state(['status_id' => $this->statusCodeActive, 'type_id' => $this->statusType])->create();
        $response = $this->getJson('/api/products/' . $product->id);
        $response->assertStatus(200);
    }

    public function testProductDeleteSuccess(): void
    {
        $token = $this->authToken(true)['token'];
        $product = Product::factory()->create();
        $response = $this->deleteJson('/api/products/' . $product->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
    }

    public function testProductPutSuccess(): void
    {
        $statuses = Status::all()->toArray();
        $product = Product::factory()->state(['status_id' => $this->statusCodeActive])->create();
        $token = $this->authToken(true)['token'];
        $response = $this->putJson('/api/products/' . $product->id, $this->productSuccess, [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function testProductPostSuccess(): void
    {
        $token = $this->authToken(true)['token'];
        $response = $this->postJson('/api/products/', $this->productSuccess, [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function testProductPostFailed(): void
    {
        $token = $this->authToken(true)['token'];
        $product = Product::factory()->state(['status_id' => $this->statusCodeActive])->create();
        $response = $this->putJson('/api/products/' . $product->id, self::PRODUCT_FAIL, [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(422); // request validate
    }

    public function testProductPatchSuccess(): void
    {
        $token = $this->authToken(true)['token'];
        $product = Product::factory()->state(['status_id' => $this->statusCodeActive])->create();
        $response = $this->patchJson('/api/products/' . $product->id, [
            'img' => 'https://google.com',
        ], [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200); // request validate
    }

    public function testProductPatchFail(): void
    {
        $token = $this->authToken(true)['token'];
        $product = Product::factory()->state(['status_id' => $this->statusCodeActive])->create();
        $response = $this->patchJson('/api/products/' . $product->id, [], [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(500); // request validate
    }

    public function testProductPutFailed(): void
    {
        $token = $this->authToken(true)['token'];
        $product = Product::factory()->state(['status_id' => $this->statusCodeActive])->create();
        $response = $this->putJson('/api/products/' . $product->id, self::PRODUCT_FAIL, [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(422); // request validate
    }

    public function testProductIdFailed(): void
    {
        $maxId = Product::max('id');
        $falseId = $maxId + 1;
        $response = $this->getJson('/api/products/' . $falseId);
        $response->assertStatus(404);
    }

    public function testAllProductsGetSuccess(): void
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'cost',
                'type_id',
                'status_id',
                'limit',
                'status' => [
                    'id',
                    'name',
                ]
            ]
        ]);
    }
}