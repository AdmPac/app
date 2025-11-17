<?php

namespace App\Services;

use App\Http\Resources\OrderResoource;
use App\Models\Order;
use App\Models\Order\Status;
use App\Repository\CartRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderService
{
    public function __construct(private CartRepository $repository)
    {
        
    }

    public function getByUserID(int $uid)
    {
        $orders = Order::where('user_id', $uid)
            ->with(['phone', 'address', 'product'])
            ->get();

        return OrderResoource::collection($orders);
    }

    public function get(): ResourceCollection
    {
        $isAdmin = Gate::allows('access-admin');
        $order = Order::query();
        $order->with('phone', 'address', 'product');
        $orders = $isAdmin ? $order->get() : $order->where('user_id', Auth::id())->get();
        return OrderResoource::collection($orders);
    }
    
    public function patch(array $request, int $id): bool
    {
        $newStatus = $request['status'];
        $newStatusCode = Status::where('id', $newStatus)->value('code');
        $order = Order::findOrFail($id);
        
        if ($newStatusCode == 1 && $id != $order->id) {
            $orderActive = $this->repository->getOrderActiveByUserId($order->user_id);
            if ($orderActive) throw new \Exception('Не может быть два заказа с статусом "Ативен');
        }

        $update = $order->update(['status_id' => $newStatus]);
        return $update;
    }
}
