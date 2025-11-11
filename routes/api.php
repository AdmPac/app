<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;

use App\Http\Controllers\AuthorizeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;


Route::post('/login', [AuthorizeController::class, 'login']); // Вход
Route::get('/products/{id}', [ProductController::class, 'get']); // Конкретный товар
Route::get('/products', [ProductController::class, 'getAll']); // Все товары

Route::middleware([
    StartSession::class
])->group(function() {
    Route::get('/cart', [CartController::class, 'get']); // Корзина
    Route::post('/cart/{id}', [CartController::class, 'post'])->whereNumber('id'); // Добавление товара в корзину
    Route::patch('/cart/{id}', [CartController::class, 'patch'])->whereNumber('id')->middleware('auth:sanctum'); // Изменение кол-ва товара в корзине
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->whereNumber('id'); // Удаление товара из корзины
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
 
    Route::post('/logout', [AuthorizeController::class, 'logout']); // Выход
    Route::get('/orders', [OrderController::class, 'get']); // Заказы текущего юзера
    Route::post('/cart/delivery', [CartController::class, 'delivery']); // Оформление доставки
});

Route::middleware(['auth:sanctum', 'can:access-admin'])->group(function() {
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
    Route::put('/products/{id}', [ProductController::class, 'put']); // Измененеие товара - полное
    Route::patch('/products/{id}', [ProductController::class, 'patch']); // Изменение товара - частичное
    Route::post('/products', [ProductController::class, 'post']); // Добавление товара
    
    Route::get('/orders/{uid}', [OrderController::class, 'getByuID']); // заказы определенного юзера
    Route::patch('/orders/{id}', [OrderController::class, 'patch']); // заказы определенного юзера
});