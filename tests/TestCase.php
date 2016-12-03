<?php namespace Arcanedev\LaravelTracker\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelTracker\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelTracker\LaravelTrackerServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            \Arcanedev\LaravelTracker\Facades\Tracker::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->settingConfigs($app['config']);
        $this->settingRoutes($app['router']);
    }

    /**
     * Setting the configs.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    private function settingConfigs($config)
    {
        $config->set('laravel-tracker.enabled', true);
    }

    /**
     * Setting the routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     */
    private function settingRoutes($router)
    {
        $router->middleware('tracked', \Arcanedev\LaravelTracker\Middleware\Tracking::class);

        $router->group(['middleware' => ['tracked']], function () use ($router) {
            $router->get('/', function () {
                return 'Tracked route';
            });
        });
    }
}
