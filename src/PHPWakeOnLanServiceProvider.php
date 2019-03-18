<?php

namespace Diegonz\PHPWakeOnLan;

use Diegonz\PHPWakeOnLan\Console\Wol;
use Diegonz\PHPWakeOnLan\Http\Controllers\PHPWakeOnLanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class PHPWakeOnLanServiceProvider.
 */
class PHPWakeOnLanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'php-wake-on-lan');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'php-wake-on-lan');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            // Registering package CLI commands.
            $this->commands([Wol::class]);
        }

        // Load views.
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'php-wake-on-lan');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/php-wake-on-lan'),
        ], 'views');

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/php-wake-on-lan'),
        ], 'assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/php-wake-on-lan'),
        ], 'lang');*/

        // Publishing config
        $this->publishes([
            __DIR__.'/../config/php-wake-on-lan.php' => config_path('php-wake-on-lan.php'),
        ], 'config');

        // Define package routes
        Route::get(config('php-wake-on-lan.route'), PHPWakeOnLanController::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/php-wake-on-lan.php', 'php-wake-on-lan');

        // Register the main class to use with the facade
        $this->app->singleton('php-wake-on-lan', function () {
            return new PHPWakeOnLan(config('php-wake-on-lan.broadcast_address'));
        });
    }
}
