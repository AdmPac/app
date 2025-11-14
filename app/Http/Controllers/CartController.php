<?php

namespace App\Http\Controllers;

use App\Contracts\CartStorageInterface;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CartController extends Controller
{

    public function __construct(private CartStorageInterface $cartStorage)
    {
        $this->cartStorage = $cartStorage;
    }
    public function get()
    {
        $productData = $this->cartStorage->get();
        return response()->json($productData);
    } 

    public function delete($id)
    {
        try {
            $result = $this->cartStorage->delete($id);
            if ($result) return response()->json('Товар успешно удален');
            return response()->json('Ошибка удаления товара');
        } catch(ModelNotFoundException | NotFoundResourceException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    
    public function post(Request $request, string $id)
    {
        try {
            $result = $this->cartStorage->post($request, $id);
            if ($result) return response()->json(['message' => 'Товар добавлен в корзину']);
            return response()->json(['message' => 'Ошибка добавления товара в корзину']);
        } catch(ModelNotFoundException | NotFoundResourceException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $result = $this->cartStorage->patch($request, $id);
            if ($result) return response()->json(['message' => 'Кол-во товара в корзине обновлено']);
            return response()->json(['message' => 'Ошибка обновления товара в корзине']);
        } catch(ModelNotFoundException | NotFoundResourceException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    
    
    public function delivery(Request $request)
    {
        try {
            $result = $this->cartStorage->delivery($request);
            if ($result) return response()->json(['message' => 'Заказ оформлен']);
            return response()->json(['message' => 'Ошибка оформления заказа']);
        } catch(ModelNotFoundException | NotFoundResourceException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
