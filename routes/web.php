<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorizeController;
use App\Http\Controllers\OrderController;

Route::get('/', [ProductController::class, 'index'])->name('product.index');

Route::get('/login', [AuthorizeController::class, 'login'])->name('login');
Route::post('/login', [AuthorizeController::class, 'check'])->name('authorize.check');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show')->whereNumber('id');

Route::get('/cart', [OrderController::class, 'index'])->name('order.index');
Route::post('/cart/{id}', [OrderController::class, 'update'])->name('order.update');
Route::delete('/cart/{orderId}/{id}', [OrderController::class, 'delete'])->name('order.delete');

Route::middleware(['auth'])->group(function() {
    Route::post('/authorize/logout/', [AuthorizeController::class, 'logout'])->name('authorize.logout');
});
Route::middleware(['auth', 'can:access-admin'])->group(function() {
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.page.edit')->whereNumber('id');
    Route::put('/product/edit/{id}', [ProductController::class, 'update'])->name('product.update')->whereNumber('id');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.delete')->whereNumber('id');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/create', [ProductController::class, 'store'])->name('product.store');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::patch('/orders/{id}/edit', [OrderController::class, 'edit'])->name('order.form.edit');
});

Route::middleware('auth')->group(function() {
    Route::get('/orders/all', [OrderController::class, 'all'])->name('order.all');
    Route::get('/orders/{id}', [OrderController::class, 'form'])->name('order.form');
    Route::post('/orders/{id}/delivery', [OrderController::class, 'delivery'])->name('order.delivery');
});