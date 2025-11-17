<?php
namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(private AuthService $service)
    {
    }

    public function register(AuthRegisterRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->service->register($validatedData);
        $token = $this->service->getToken($user);
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $token = $this->service->login($request);
        return response()->json([
            'token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function logout()
    {
        $this->service->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getUser()
    {
        $user = $this->service->getUser();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function updateUser(Request $request)
    {
        $user = $this->service->updateUser($request);
        return response()->json($user);
    }
}