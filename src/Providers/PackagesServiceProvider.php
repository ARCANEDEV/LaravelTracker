<?php namespace Arcanedev\LaravelTracker\Providers;

use Arcanedev\Support\ServiceProvider;

/**
 * Class     PackagesServiceProvider
 *
 * @package  Arcanesoft\Tracker\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PackagesServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the dependencies.
     */
    public function register()
    {
        $this->registerProviders([
            \Arcanedev\GeoIP\GeoIPServiceProvider::class,
            \Arcanedev\Agent\AgentServiceProvider::class,
        ]);
    }
}
