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
        $this->mergeConfigFrom(__DIR__.'/config/users_system.php', 'users_system');   
    }
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Publicar migraciones propias (si quieres agregar migraciones custom)
        // $this->publishes([
        //     __DIR__.'/../database/migrations/' => database_path('migrations'),
        // ], 'users-system-migrations');
    }

}