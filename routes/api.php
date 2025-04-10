<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;

Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
        
        Route::apiResource('players', PlayerController::class);
        Route::apiResource('tournaments', TournamentController::class)->only(['store', 'index', 'show']);
        Route::post('tournaments/{id}/simulate', [TournamentController::class, 'simulate']);

    });

});

