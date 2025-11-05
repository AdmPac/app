<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorizeController;

Route::get('/', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
Route::post('/product/create', [ProductController::class, 'store'])->name('product.store');

Route::get('/product/{id}', [ProductController::class, 'edit'])->name('product.page.edit')->whereNumber('id');
Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update')->whereNumber('id');
Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.delete')->whereNumber('id');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

Route::get('/authorize', [AuthorizeController::class, 'authorize'])->name('authorize');
Route::post('/authorize', [AuthorizeController::class, 'check'])->name('authorize.check');
Route::post('/authorize/logout/', [AuthorizeController::class, 'logout'])->name('authorize.logout');

