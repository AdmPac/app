<?php

namespace Tests\Feature\Traits;

use App\Models\User;

trait AuthTests
{
    protected function authToken()
    {
        $mail = $this->faker->unique()->safeEmail();
        $password = 'wdasdas';
        
        User::create([
            'name' => $this->faker->name(),
            'email' => $mail,
            'email_verified_at' => now(),
            'password' => $password,
        ]);
        
        $loginData = [
            'email' => $mail,
            'password' => $password,
        ];
        $response = $this->post('/api/login', $loginData);
        $response->assertStatus(200);

        $token = $response->json('token');

        return $token;
    }
}