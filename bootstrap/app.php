<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load API versioned routes
            Route::middleware(['api', 'auth:sanctum'])
                ->prefix('api/v1')
                ->as('api.v1.')
                ->group(__DIR__.'/../routes/api/v1.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Ensure API responses are wrapped consistently; run this as the outermost API middleware
        $middleware->prependToGroup('api', App\Http\Middleware\ApiResponseMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
