<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\OptionalJwtMiddleware;

Route::get('/products/{id}', [ProductController::class, 'get']); // Конкретный товар
Route::get('/products', [ProductController::class, 'getAll']); // Все товары

Route::middleware([
    StartSession::class,
    OptionalJwtMiddleware::class,
])->group(function() {
    Route::get('/cart', [CartController::class, 'get']); // Корзина
    Route::post('/cart/{id}', [CartController::class, 'post'])->whereNumber('id'); // Добавление товара в корзину
    Route::patch('/cart/{id}', [CartController::class, 'patch'])->whereNumber('id')->middleware('jwt.auth'); // Изменение кол-ва товара в корзине
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->whereNumber('id'); // Удаление товара из корзины
});

Route::middleware('jwt.auth')->group(function() {
    Route::get('/orders', [OrderController::class, 'get']); // Заказы текущего юзера
    Route::post('/cart/delivery', [CartController::class, 'delivery']); // Оформление доставки
});

Route::middleware(['jwt.auth', 'can:access-admin'])->group(function() {
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
    Route::put('/products/{id}', [ProductController::class, 'put']); // Измененеие товара - полное
    Route::patch('/products/{id}', [ProductController::class, 'patch']); // Изменение товара - частичное
    Route::post('/products', [ProductController::class, 'post']); // Добавление товара
    
    Route::get('/orders/{uid}', [OrderController::class, 'getByID']); // заказы определенного юзера
    Route::patch('/orders/{id}', [OrderController::class, 'patch']); // заказы определенного юзера
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::put('/user', [AuthController::class, 'updateUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/healthcheck', function () {
    return response()->json(['status' => 'ok'], 200);
});