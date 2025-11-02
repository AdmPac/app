<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;

Route::get('/', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{id}', [ProductController::class, 'edit'])->name('product.page.edit');
Route::put('/product/{id}', [ProductController::class, 'store'])->name('product.edit');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

