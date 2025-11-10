<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;

use App\Http\Controllers\AuthorizeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;


Route::post('/login', [AuthorizeController::class, 'login']);
Route::get('/products/{id}', [ProductController::class, 'get']);
Route::get('/products', [ProductController::class, 'getAll']);

Route::middleware([
    StartSession::class
])->group(function() {
    Route::get('/cart', [CartController::class, 'get']);
    Route::post('/cart/{id}', [CartController::class, 'post'])->whereNumber('id');
    Route::patch('/cart/{id}', [CartController::class, 'patch'])->whereNumber('id');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->whereNumber('id');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
 
    Route::post('/logout', [AuthorizeController::class, 'logout']);
    Route::get('/orders', [OrderController::class, 'get']); // заказы текущего юзера
    Route::post('/cart/delivery', [CartController::class, 'delivery']);
});

Route::middleware(['auth:sanctum', 'can:access-admin'])->group(function() {
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
    Route::put('/products/{id}', [ProductController::class, 'put']);
    Route::patch('/products/{id}', [ProductController::class, 'patch']);
    Route::post('/products', [ProductController::class, 'post']);
    
    Route::get('/orders/{uid}', [OrderController::class, 'getByuID']); // заказы определенного юзера
});