<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            $orders = OrderItems::with('order', 'product')->get();
            dd($orders[0]->order->product[0]->order->toArray());
        } else {
            $quantity = session('order') ?? [];
            $products = Product::whereIn('id', array_keys($quantity))->get();
        }

        return view('order.index', compact('products', 'quantity'));
    }

    public function delete($id)
    {
        $order = session('order');
        if (isset($order[$id])) unset($order[$id]);

        session(['order' => $order]);
        return redirect()->route('order.index');
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $limit = Product::where('id', $id)->pluck('limit')->first();

        $request->validate([
            'quantity' => "integer|min:1|max:$limit"
        ]);

        $order = session('order');
        if (!is_array($order) || !key_exists($id, $order)) $order[$id] = 0; 
        if (($order[$id] += $request->quantity) > $limit) $order[$id] = $limit;
        
        session(['order' => $order]);
        return redirect()->route('order.index');
    }
}
