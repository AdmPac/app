<?php

namespace App\Http\Controllers;

use App\Contracts\CartStorageInterface;
use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartDeliveryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CartController extends Controller
{

    public function __construct(private CartStorageInterface $cartStorage)
    {
    }
    
    public function get(): JsonResponse
    {
        $productData = $this->cartStorage->get();
        return response()->json($productData);
    } 

    public function delete($id): JsonResponse
    {
        $result = $this->cartStorage->delete($id);
        if ($result) return response()->json('Товар успешно удален');
        return response()->json('Ошибка удаления товара', 500);
    }
    
    public function post(CartAddRequest $request, string $id): JsonResponse
    {
        $result = $this->cartStorage->post($request->validated(), $id);
        if ($result) return response()->json(['message' => 'Товар добавлен в корзину']);
        return response()->json(['message' => 'Ошибка добавления товара в корзину'], 500);
    }

    public function patch(CartAddRequest $request, $id): JsonResponse
    {
        $result = $this->cartStorage->patch($request->validated(), $id);
        if ($result) return response()->json(['message' => 'Кол-во товара в корзине обновлено']);
        return response()->json(['message' => 'Ошибка обновления товара в корзине'], 500);
    }
    
    
    public function delivery(CartDeliveryRequest $request): JsonResponse
    {
        $result = $this->cartStorage->delivery($request->validated());
        if ($result) return response()->json(['message' => 'Заказ оформлен']);
        return response()->json(['message' => 'Ошибка оформления заказа'], 500);
    }
}
