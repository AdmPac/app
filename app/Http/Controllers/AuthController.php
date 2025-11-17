<?php
namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(private AuthService $service)
    {
    }

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $user = $this->service->register($validatedData);
        $token = $this->service->getToken($user);
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $token = $this->service->login($request);
        return response()->json([
            'token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->service->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getUser(): JsonResponse
    {
        $user = $this->service->getUser();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function updateUser(Request $request): JsonResponse
    {
        $user = $this->service->updateUser($request);
        return response()->json($user);
    }
}