<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Tymon\JWTAuth\Exceptions\JWTException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias ([ 
            'jwt' => JwtMiddleware::class
        ]); 
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function(ModelNotFoundException | NotFoundResourceException $e, Request $request) {
            if ($request->expectsJson()) return response()->json(['message' => $e->getMessage()], 404);
            return false;
        });
        $exceptions->render(function(HttpExceptionInterface $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    ['message' => $e->getMessage()],
                    $e->getStatusCode(),
                    $e->getHeaders()
                );
            }
            return false;
        });
        $exceptions->render(function(AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) return response()->json(['message' => $e->getMessage() ?: 'Unauthenticated'], 401);
            return false;
        });
        $exceptions->render(function(AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) return response()->json(['message' => $e->getMessage() ?: 'Forbidden'], 403);
            return false;
        });
        $exceptions->render(function(ThrottleRequestsException $e, Request $request) {
            if ($request->expectsJson()) return response()->json(['message' => $e->getMessage() ?: 'Too Many Requests'], 429);
            return false;
        });
        $exceptions->render(function(ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Ошибка валидации',
                    'errors' => $e->errors(),
                ], 422);
            }
            return false;
        });
        $exceptions->render(function(\Exception $e, Request $request) {
            if ($request->expectsJson()) return response()->json(['message' => $e->getMessage()], 500);
            return false;
        });
        $exceptions->render(function(JWTException $e, Request $request) {
            if ($request->expectsJson()) return response()->json(['message' => $e->getMessage()], 500);
            return false;
        });
    })->create();
