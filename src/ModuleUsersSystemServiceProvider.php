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
        $this->mergeConfigFrom(__DIR__.'/config/module-users-system.php', 'users_system');   
    }
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }

}