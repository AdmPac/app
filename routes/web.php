<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketController;

Route::get('/', [MarketController::class, 'index']);

