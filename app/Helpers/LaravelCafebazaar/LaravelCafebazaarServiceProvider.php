<?php

namespace App\Helpers\LaravelCafebazaar;
use App\Helpers\LaravelCafebazaar\LaravelCafebazaar;
use Illuminate\Support\ServiceProvider;

class LaravelCafebazaarServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-cafebazaar.php'),
            ], 'config');

            // Registering package commands.
            $this->commands([LaravelCafebazaarConsole::class]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(config_path('laravel-cafebazaar.php') , 'laravel-cafebazaar');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-cafebazaar', function () {
            return new LaravelCafebazaar;
        });
    }
}