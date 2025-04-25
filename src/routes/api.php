<?php

use Illuminate\Support\Facades\Route;
use Vitacode\ModuleUsersSystem\Controllers\AuthController;

Route::prefix('api/auth')->group(function () {
    if (config('users_system.routes.login')) {
        Route::post('login', [AuthController::class, 'login']);
    }

    if (config('users_system.routes.logout')) {
        Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
    }
});