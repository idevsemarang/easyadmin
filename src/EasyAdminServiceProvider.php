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
        $this->app->make('request');

        $this->publishes([
            __DIR__.'/public' => public_path('easyadmin'),
        ], 'public');
        $this->publishes([
            __DIR__.'/app/Helpers/Sidebar.php' => app_path('Helpers/Sidebar.php'),
        ], 'sidebar');
        $this->publishes([
            __DIR__.'/app/Controllers/SampleDataController.php' => app_path('Controllers/SampleDataController.php'),
        ], 'sample-crud');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/database/seeders' => database_path('seeders'),
                __DIR__.'/database/migrations' => database_path('migrations'),
            ], 'seeds-migrations');
        }
    }
}
