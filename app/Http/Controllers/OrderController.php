<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Order\Status;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    public function edit(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status_id' => $request->status_id]);
        return back();
    }

    public function form($id)   
    {
        $isAdmin = Gate::allows('access-admin');
        $uid = Auth::user()->id;
        $orderUser = Order::findOrFail($id);
        if (!$isAdmin && $uid != $orderUser->user_id) return redirect('orders');

        $returnData = [];

        $returnData['address'] = $orderUser->address()->first() ? $orderUser->address()->first()->value : '';
        $returnData['phone'] = $orderUser->phone()->first() ? $orderUser->phone()->first()->value : '';
        $productData = $orderUser->product->toArray();
        
        $returnData['orderId'] = $orderUser->id;
        $returnData['allSum'] = 0;
        $returnData['allCnt'] = 0;

        if ($isAdmin) {
            $returnData['isAdmin'] = $isAdmin;
            $returnData['allStatus'] = Status::all();
        }

        $statusModel = $orderUser->status;
        $returnData['statusId'] = $statusModel->id;
        $returnData['statusName'] = $statusModel->value;
        $returnData['isCart'] = $orderUser->status->id === 1;

        foreach ($productData as $data) {
            $quantity = $data['pivot']['quantity'];
            $cost = $returnData['isCart'] ? $data['cost'] : $data['pivot']['cost'];
            $returnData['allSum'] += $quantity * $cost;
            $returnData['allCnt'] += $quantity;
        }

        // return view('order.delivery', compact('returnData'));
    }

   
}
