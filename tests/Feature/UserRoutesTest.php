<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\AuthTests;
use Tests\TestCase;

class UserRoutesTest extends TestCase
{

    use RefreshDatabase, WithFaker, AuthTests;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->authToken();
    }

    public function testUser(): void
    {   
        $response = $this->get('/api/user', [
            'Authorization' => "Bearer {$this->token}",
        ]);
        $response->assertStatus(200);
    }

    public function testGuest(): void
    {   
        $response = $this->get('/api/user');
        $response->assertStatus(401);
    }

    public function testUserBadToken(): void
    {
        $token = 'test.bad.token';
        $response = $this->get('/api/user', [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(401);
    }
    
    public function testUserLogout(): void
    {   
        $response = $this->post('/api/logout', [], [
            'Authorization' => "Bearer {$this->token}",
        ]);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Successfully logged out']);
    }
}
