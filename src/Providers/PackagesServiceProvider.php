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
        $this->app->register(\Arcanedev\GeoIP\GeoIPServiceProvider::class);
        $this->app->register(\Jenssegers\Agent\AgentServiceProvider::class);
    }
}
