<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\WeddingController;

// API V1 routes (prefix and names are applied from bootstrap/app.php)
Route::apiResource('weddings', WeddingController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);
