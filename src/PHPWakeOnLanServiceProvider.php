<?php

namespace Diegonz\PHPWakeOnLan;

use Diegonz\PHPWakeOnLan\Console\Wol;
use Illuminate\Support\ServiceProvider;

/**
 * Class PHPWakeOnLanServiceProvider.
 */
class PHPWakeOnLanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        // Registering package CLI commands.
        if ($this->app->runningInConsole()) {
            $this->commands([Wol::class]);
        }

        // Publishing config
        $this->publishes([__DIR__.'/../config/php-wake-on-lan.php' => config_path('php-wake-on-lan.php')], 'config');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/php-wake-on-lan.php', 'php-wake-on-lan');

        // Register the main class to use with the facade
        $this->app->singleton('php-wake-on-lan', static function () {
            return new PHPWakeOnLan(config('php-wake-on-lan.broadcast_address'), config('php-wake-on-lan.port'));
        });
    }
}
