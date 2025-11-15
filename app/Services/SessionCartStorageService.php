<?php

namespace App\Services;

use App\Contracts\CartStorageInterface;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class SessionCartStorageService implements CartStorageInterface
{
	public function get(): array
	{
		$quantity = session('order') ?? [];
		$productsData = Product::whereIn('id', array_keys($quantity))->get();
		$productsData = $productsData->map(function($product) use ($quantity) {
			$product->quantity = $quantity[$product->id] ?? 0;
			return $product;
		});
		$productsData = $productsData->toArray();

		return $productsData;
	}

	public function delete($id): bool
	{
		$order = session('order');
		if (isset($order[$id])) unset($order[$id]);
		else throw new NotFoundResourceException("Продукт $id не найден в корзине");

		return true;
	}

	public function post(Request $request, string $id): bool
	{
		$product = Product::findOrFail($id);
		$limit = $product->limit;
		$request->validate([
			'quantity' => "required|integer|min:1|max:$limit"
		]);

		$quantity = $request->quantity;

		$order = Session::get('order', []);
		if (!key_exists($id, $order)) $order[$id] = 0; 
		if (($order[$id] += $quantity) > $limit) $order[$id] = $limit;
		
		Session::put('order', $order);
		
		return true;
	}

	public function patch(Request $request, $id): bool
	{
		$product = Product::findOrFail($id);
		$limit = $product->limit;
		$request->validate([
			'quantity' => "required|integer|min:1|max:$limit"
		]);
		$quantity = $request->quantity;

		$order = Session::get('order', []);
		if (!key_exists($id, $order)) throw new NotFoundResourceException("Продукт $id не найден в корзине");
		$order[$id] = min($limit, $quantity);
		Session::put('order', $order);

		return true;
	}

	public function delivery(Request $request): bool
	{
		throw new UnauthorizedHttpException('Bearer', 'Авторизуйтесь для оформления заказа');
	}
}
