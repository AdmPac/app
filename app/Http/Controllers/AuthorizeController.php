<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email' ,
            'password' => 'required|string',
        ]);

        if($user = Auth::attempt($loginData)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            if ($token) {
                return response()->json([
                    'message' => 'Успешный вход',
                    'token' => $token,
                ]);
            }
            return response()->json([
                'message' => 'Ошибка входа',
                'token' => $token,
            ], 500);
        }

        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Успешный выход',
        ]);
    }
}