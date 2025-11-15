<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function __construct(private UserRepository $repository) {}

    public function register(array $request): User
    {
        $user = $this->repository->create($request);
        return $user;
    }

    public function getToken(User $user): string
    {
        $token = JWTAuth::fromUser($user);
        return $token;

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
        $this->repository->update($user, $request->only(['name', 'email']));
        return $user;
    }
}
