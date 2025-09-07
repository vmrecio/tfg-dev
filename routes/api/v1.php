<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\WeddingController;

// API V1 routes (prefix and name are applied from bootstrap/app.php)
Route::get('weddings', [WeddingController::class, 'index'])->name('weddings.index');

