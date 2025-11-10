<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
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
            
            $uid = Auth::user()->id;
            $orderModel = Order::firstOrCreate([
                'user_id' => $uid,
                'status_id' => 1,
            ]);
            
            if ($orderModel->product()->first() === null) {
                $orderSession = session('order');
                $products = Product::whereIn('id', array_keys($orderSession))->get()->toArray();
                $products = array_column($products, null, 'id');
                
                foreach ($orderSession as $productId => $quantity) {
                    // dump($products, $productId);
                    OrderItems::create([
                        'order_id' => $orderModel->id,
                        'product_id' => $productId,
                        'name' => $products[$productId]['name'],
                        'img' => $products[$productId]['img'],
                        'description' => $products[$productId]['description'],
                        'cost' => $products[$productId]['cost'],
                        'quantity' => $quantity,
                    ]);
                }
            }
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
