<?php

namespace IvanoMatteo\LaravelDbMutex;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaravelDbMutexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->mergeConfigFrom(__DIR__ . '/../config/LaravelDbMutex.php', 'laravel-db-mutex');
        //$this->publishConfig();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');        
    }



    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Publish Config
     *
     * @return void
     */
    public function publishConfig()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/LaravelDbMutex.php' => config_path('LaravelDbMutex.php'),
            ], 'config');
        }
    }
}
