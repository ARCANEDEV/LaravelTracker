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
        $this->settingDatabase($app['config']);
        $this->settingConfigs($app['config']);
        $this->settingRoutes($app['router']);
    }

    /**
     * Setting the database.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    private function settingDatabase($config)
    {
        $config->set('database.default', 'testing');
        $config->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Setting the configs.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    private function settingConfigs($config)
    {
        $config->set('laravel-tracker.enabled', true);
        $config->set('laravel-tracker.database.connection', 'testing');
    }

    /**
     * Setting the routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     */
    private function settingRoutes($router)
    {
        $router->middleware('tracked', \Arcanedev\LaravelTracker\Middleware\Tracking::class);

        $attributes = version_compare('5.2.0', app()->version(), '<=')
            ? ['middleware' => ['web', 'tracked']]
            : ['middleware' => 'tracked'];

        $router->group($attributes, function () use ($router) {
            $router->get('/', function () {
                return 'Tracked route';
            });
        });
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Migrate the migrations.
     */
    protected function migrate()
    {
        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath' => $this->getMigrationsSrcPath(),
        ]);

        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__ . '/fixtures/migrations'),
        ]);
    }

    /**
     * Get the migrations source path.
     *
     * @return string
     */
    protected function getMigrationsSrcPath()
    {
        return realpath(dirname(__DIR__) . '/database/migrations');
    }

    /**
     * Get the migrations destination path.
     *
     * @return string
     */
    protected function getMigrationsDestPath()
    {
        return realpath(database_path('migrations'));
    }

    /**
     * Publish the migrations.
     */
    protected function publishMigrations()
    {
        $this->artisan('vendor:publish', [
            '--tag' => ['migrations'],
        ]);
    }
}
