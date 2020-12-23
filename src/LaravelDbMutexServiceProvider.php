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
        $this->mergeConfigFrom(__DIR__ . '/../config/LaravelDbMutex.php', 'laravel-db-mutex');

        $this->publishConfig();

        // $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-db-mutex');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->registerRoutes();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });
    }

    /**
    * Get route group configuration array.
    *
    * @return array
    */
    private function routeConfiguration()
    {
        return [
            'namespace'  => "IvanoMatteo\LaravelDbMutex\Http\Controllers",
            'middleware' => 'api',
            'prefix'     => 'api'
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register facade
        //$this->app->singleton('laravel-db-mutex', function () {
        //    return new LaravelDbMutex;
        //});
        //"aliases": {
        //    "IvanoMatteo": "IvanoMatteo\\LaravelDbMutex\\LaravelDbMutexFacade"
        //}
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
