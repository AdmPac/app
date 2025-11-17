<?php

namespace App\Http\Controllers;

use App\Contracts\CartStorageInterface;
use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartPatchRequest;
use App\Http\Requests\CartDeliveryRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CartController extends Controller
{

    public function __construct(private CartStorageInterface $cartStorage)
    {
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
    
    public function post(CartAddRequest $request, string $id)
    {
        try {
            $result = $this->cartStorage->post($request, $id);
            if ($result) return response()->json(['message' => 'Товар добавлен в корзину']);
            return response()->json(['message' => 'Ошибка добавления товара в корзину']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch(ModelNotFoundException | NotFoundResourceException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function patch(CartAddRequest $request, $id)
    {
        try {
            $result = $this->cartStorage->patch($request, $id);
            if ($result) return response()->json(['message' => 'Кол-во товара в корзине обновлено']);
            return response()->json(['message' => 'Ошибка обновления товара в корзине']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch(ModelNotFoundException | NotFoundResourceException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    
    public function delivery(CartDeliveryRequest $request)
    {
        try {
            $result = $this->cartStorage->delivery($request);
            if ($result) return response()->json(['message' => 'Заказ оформлен']);
            return response()->json(['message' => 'Ошибка оформления заказа']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch(ModelNotFoundException | NotFoundResourceException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
