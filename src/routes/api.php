<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;

Route::post('register', [AuthController::class, 'register']);

Route::prefix('games/{token}')->group(function () {
    Route::get('/', [GameController::class, 'show']);
    Route::post('play', [GameController::class, 'store']);
    Route::get('results', [GameController::class, 'index']);
    Route::post('regenerate', [GameController::class, 'regenerate']);
    Route::post('deactivate', [GameController::class, 'deactivate']);
});
