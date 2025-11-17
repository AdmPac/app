<?php

namespace App\Providers;

use App\Contracts\CartStorageInterface;
use App\Services\DatabaseCartStorageService;
use App\Services\SessionCartStorageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CartStorageInterface::class, function ($app) {
            if (Auth::check()) {
                return $app->make(DatabaseCartStorageService::class);
            }
            return $app->make(SessionCartStorageService::class);
        });
    }

    public function boot(): void
    {
        //
    }
}
