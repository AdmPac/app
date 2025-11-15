<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function register(Request $request): array
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);
        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function login(Request $request): string
    {
        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);
        
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new JWTException('Invalid credentials', 401);
        }
        return $token;
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function getUser(): User
    {
        $user = Auth::user();
        return $user;
    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only(['name', 'email']));
        return $user;
    }
}
