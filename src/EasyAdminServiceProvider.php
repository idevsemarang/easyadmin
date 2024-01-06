<?php

namespace Idev\EasyAdmin;

use Illuminate\Support\ServiceProvider;

class EasyAdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'easyadmin');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations', 'easyadmin');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(base_path('/routes/web.php'));
        $this->app->make('request');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/public' => public_path('easyadmin'),
                __DIR__.'/app/Helpers/Sidebar.php' => app_path('Helpers/Sidebar.php'),
                __DIR__.'/app/Models/User.php' => app_path('Models/User.php'),
                __DIR__.'/database/seeders' => database_path('seeders'),
                __DIR__.'/database/migrations' => database_path('migrations'),
                __DIR__.'/config/idev.php' => config_path('idev.php'),
                
            ], 'install-idev');
        }
        
        $this->publishes([
            __DIR__.'/app/Models/SampleData.php' => app_path('Models/SampleData.php'),
            __DIR__.'/app/Http/Controllers/SampleDataController.php' => app_path('Http/Controllers/SampleDataController.php'),
        ], 'sample-crud');
    }
}
