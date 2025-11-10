<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorizeController;
use App\Http\Controllers\OrderController;

// Route::middleware(['auth', 'can:access-admin'])->group(function() {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
//     Route::patch('/orders/{id}/edit', [OrderController::class, 'edit'])->name('order.form.edit');
// });

// Route::middleware('auth')->group(function() {
//     Route::get('/orders/{id}', [OrderController::class, 'form'])->name('order.form');
//     Route::post('/orders/{id}/delivery', [OrderController::class, 'delivery'])->name('order.delivery');
// });