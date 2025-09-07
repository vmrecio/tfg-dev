<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\WeddingController;
use App\Http\Controllers\Api\V1\AuthController;

// API V1 routes (prefix and names are applied from bootstrap/app.php)
Route::post('auth/login', [AuthController::class, 'login'])
    ->name('auth.login')
    ->withoutMiddleware('auth:sanctum');

Route::post('auth/logout', [AuthController::class, 'logout'])
    ->name('auth.logout');

Route::apiResource('weddings', WeddingController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);
