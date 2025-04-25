<?php

use Illuminate\Support\Facades\Route;
use Vitacode\ModuleUsersSystem\Controllers\AuthController;

Route::prefix(config('users_system.route_prefix', 'api/auth'))->middleware(config('users_system.middleware', ['api']))->group(function () {
    if (config('users_system.routes.login')) {
        Route::post('login', [AuthController::class, 'login']);
    }

    if (config('users_system.routes.logout')) {
        Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
    }
});