<?php

use Illuminate\Support\Facades\Route;
use Vitacode\ModuleUsersSystem\Controllers\AuthController;

Route::prefix('api/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});