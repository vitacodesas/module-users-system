<?php

use Illuminate\Support\Facades\Route;
use Vitacode\ModuleUsersSystem\Http\Controllers\AuthController;
use Vitacode\ModuleUsersSystem\Http\Controllers\RoleController;
use Vitacode\ModuleUsersSystem\Http\Controllers\PermissionController;
use Vitacode\ModuleUsersSystem\Http\Controllers\UserRoleController;

Route::prefix(config('users_system.route_prefix', 'api/auth'))
    ->middleware(config('users_system.middleware', ['api']))
    ->group(function () {

        // Rutas de autenticación
        if (config('users_system.routes.login')) {
            Route::post('login', [AuthController::class, 'login']);
        }

        if (config('users_system.routes.logout')) {
            Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
        }

        // Rutas de roles
        if (config('users_system.routes.roles', true)) {
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('roles', [RoleController::class, 'store']); // Crear rol
                Route::get('roles', [RoleController::class, 'index']); // Listar roles
                Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions']); // Asignar permisos a rol
            });
        }

        // Rutas de permisos
        if (config('users_system.routes.permissions', true)) {
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('permissions', [PermissionController::class, 'store']); // Crear permiso
                Route::get('permissions', [PermissionController::class, 'index']); // Listar permisos
            });
        }

        // Rutas de roles de usuario
        Route::middleware('auth:sanctum')->group(function () {
            // Asignar rol a un usuario
            Route::post('users/{userId}/roles', [UserRoleController::class, 'assignRole']);

            // Quitar rol de un usuario
            Route::delete('users/{userId}/roles', [UserRoleController::class, 'removeRole']);
        });
    });