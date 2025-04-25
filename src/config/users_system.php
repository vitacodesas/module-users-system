<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rutas habilitadas
    |--------------------------------------------------------------------------
    |
    | Puedes desactivar rutas específicas que no quieras exponer desde el paquete.
    |
    */

    'routes' => [
        'login' => true,
        'logout' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Modelo de usuario
    |--------------------------------------------------------------------------
    |
    | Puedes definir qué modelo de usuario se usará para autenticación.
    |
    */

    'user_model' => App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Nombre del token Sanctum
    |--------------------------------------------------------------------------
    |
    | Personaliza el nombre del token creado en login.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Prefijo de rutas
    |--------------------------------------------------------------------------
    |
    | Permite modificar el prefijo (por defecto: "auth").
    |
    */

    'route_prefix' => 'api/auth',

    /*
    |--------------------------------------------------------------------------
    | Middleware de rutas
    |--------------------------------------------------------------------------
    |
    | Puedes modificar los middlewares aplicados a las rutas protegidas.
    |
    */

    'middleware' => [
        'api',
    ],
];