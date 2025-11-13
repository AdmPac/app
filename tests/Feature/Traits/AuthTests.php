<?php

namespace Tests\Feature\Traits;

use App\Models\User;

trait AuthTests
{
    protected function authToken($isAdmin = false)
    {
        $mail = $this->faker->unique()->safeEmail();
        $password = 'wdasdas';
        
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $mail,
            'email_verified_at' => now(),
            'password' => $password,
            'is_admin' => $isAdmin
        ]);
        
        $this->actingAs($user, 'api');
        
        $loginData = [
            'email' => $mail,
            'password' => $password,
        ];
        $response = $this->post('/api/login', $loginData);
        $token = $response->json('token');
        return ['token' => $token, 'user' => $user];
    }
}