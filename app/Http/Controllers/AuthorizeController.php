<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login()
    {
        if (Auth::user()) return redirect()->route('product.index');
        return view('authorize');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function check(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($loginData)) {
            return back();
        }
        return back()->withErrors(['email' => "Неверные данные"])->onlyInput();
    }

    public function logout()
    {
        Auth::logout();
        return back();
    }
}
