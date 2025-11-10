<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Order\Status;
use App\Models\OrderItems;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function delivery(Request $request, $id)
    {
        // TODO: заменить на Request class
        $request->validate([
            'phone' => 'required|regex:/^\+?[0-9-]{9,}$/',
            'address' => 'required|min:1',
        ]);
        
        $address = Address::firstOrCreate([
            'value' => $request->address,
        ]);
        $phone = Phone::firstOrCreate([
            'value' => $request->phone,
        ]);
        
        // TODO: Заменить на политику
        $uid = Auth::user()->id;
        $orderUser = Order::findOrFail($id);
        if ($uid != $orderUser->user_id || $orderUser->status_id != 1) return redirect('orders');
        
        $orderUser->update([
            'status_id' => 2,
            'address_id' => $address->id,
            'phone_id' => $phone->id,
        ]);
        return back();
    }

    public function all()
    {
        $isAdmin = Gate::allows('access-admin');
        $orders = $isAdmin ? Order::all() : Order::where('user_id', Auth::id())->get();
        $orderData = [];
        $allStatus = $isAdmin ? Status::all()->toArray() : null;
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
        return view('order.all', compact('orderData')); // isAdmin - чтобы allStatus не был единственным параметром для получения доп.доступов
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

        return view('order.delivery', compact('returnData'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderId = null;
        $productsData = [];
        if (Auth::check()) {
            $productsModel = Order::with('product', 'status')
                ->where('user_id', Auth::user()->id)
                ->where('status_id', 1) // статус=1 - текущая корзина
                ->first();
            if ($productsModel) {
                $orderId = $productsModel->id;
    
                $productsData = $productsModel ? $productsModel->toArray()['product'] : [];
                foreach ($productsData as $k => $product) {
                    $productsData[$k]['quantity'] = $product['pivot']['quantity'];
                    $productsData[$k]['order_id'] = $product['pivot']['order_id'];
                }
            }
        } else {
            $quantity = session('order') ?? [];
            $productsData = Product::whereIn('id', array_keys($quantity))->get();
            $productsData = $productsData->map(function($product) use ($quantity) {
                $product->quantity = $quantity[$product->id] ?? 0;
                return $product;
            });
            $productsData = $productsData->toArray();
        }
        $allSum = 0;
        foreach ($productsData as $product) {
            $allSum += $product['quantity'] * $product['cost'];
        }
        return view('order.index', compact('productsData', 'allSum', 'orderId'));
    }

    public function delete($orderId, $id)
    {
        if (Auth::check()) {
            $item = OrderItems::where(['order_id' => $orderId, 'product_id' => $id])->first();
            $item->delete();
        } else {
            $order = session('order');
            if (isset($order[$id])) unset($order[$id]);
            session(['order' => $order]);
        }
        return redirect()->route('order.index');
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $limit = $product->limit;
        $request->validate([
            'quantity' => "integer|min:1|max:$limit"
        ]);
        $quantity = $request->quantity;
        if (Auth::check()) {
            $cart = Order::firstOrCreate([
                'user_id' => Auth::user()->id,
                'status_id' => 1,
            ]);
            
            $item = OrderItems::firstOrCreate([
                'product_id' => $id,
                'order_id' => $cart->id,
            ],[
                'name' => $product->name,
                'img' => $product->img,
                'description' => $product->description,
                'cost' => $product->cost,
                'name' => $product->name,
                'quantity' => 0,
            ]);
            if (($quantity += $item->quantity) > $limit) $quantity = $limit;

            $item->update(['quantity' => $quantity]);
        } else {
            $order = session('order');
            if (!is_array($order) || !key_exists($id, $order)) $order[$id] = 0; 
            if (($order[$id] += $quantity) > $limit) $order[$id] = $limit;
            
            session(['order' => $order]);
        }
        return redirect()->route('order.index');
    }
}
