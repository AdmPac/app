<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function getByuID($uid)
    {
        $orders = Order::where('user_id', $uid)->get();
        $orderData = [];
        
        foreach ($orders as &$order) {
            $phone = $order->phone ? $order->phone->value : '';
            $address = $order->address ? $order->address->value : '';
            $orderData[$order->id] = [
                'id' => $order->id,
                'status' => $order->status->value,
                'phone' => $phone,
                'address' => $address,
            ];
            $products = $order->product()->get();
            foreach ($products as $product) {
                $orderData[$order->id]['name'] = $product->pivot->name;
                $orderData[$order->id]['quantity'] = $product->pivot->quantity;
                $orderData[$order->id]['cost'] = $product->pivot->cost;
                $orderData[$order->id]['cost_all'] = $product->pivot->cost;
            }
        }
        return response()->json(array_values($orderData));
    }

    public function get()
    {
        $isAdmin = Gate::allows('access-admin');
        $orders = $isAdmin ? Order::all() : Order::where('user_id', Auth::id())->get();
        $orderData = [];
        
        foreach ($orders as &$order) {
            $phone = $order->phone ? $order->phone->value : '';
            $address = $order->address ? $order->address->value : '';
            $orderData[$order->id] = [
                'id' => $order->id,
                'status' => $order->status->value,
                'phone' => $phone,
                'address' => $address,
            ];
            $products = $order->product()->get();
            foreach ($products as $product) {
                $orderData[$order->id]['name'] = $product->pivot->name;
                $orderData[$order->id]['quantity'] = $product->pivot->quantity;
                $orderData[$order->id]['cost'] = $product->pivot->cost;
                $orderData[$order->id]['cost_all'] = $product->pivot->cost;
            }
        }
        return response()->json(array_values($orderData));
    }

    public function patch(Request $request, $id)
    {
        $statuses = Status::all()->toArray();
        $request->validate([
            'status' => [
                'required',
                'integer',
                Rule::in(array_column($statuses, 'id')),
            ],
        ]);
        $newStatus = $request->status;
        $newStatusCode = Status::where('id', $newStatus)->value('code');
        $order = Order::findOrFail($id);
        
        if ($newStatusCode == 1 && $id != $order->id) {
            $orderActive = Order::where('user_id', $order->user_id)
                ->whereHas('status', function ($q) {
                    $q->where('code', 1);
                })
                ->first();
            if ($orderActive) return response()->json([
                'message' => 'Не может быть два заказа с статусом "Ативен"', 500
            ]);
        }

        $update = $order->update(['status_id' => $newStatus]);
        if ($update) {
            return response()->json($order);
        }
        return response()->json([
            'message' => 'Ошибка обновления',
        ], 500);
    }
}
