<?php

namespace Vitacode\ModuleUsersSystem;

use Illuminate\Support\ServiceProvider;

class ModuleUsersSystemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Carga las rutas del paquete
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Publica las migraciones si las hubiera
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Opcional: publicar archivos si el usuario quiere personalizar
        $this->publishes([
            __DIR__.'/../config/module-users-system.php' => config_path('module-users-system.php'),
        ], 'config');
    }
}