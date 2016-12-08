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
        $config->set('laravel-tracker.tracking', [
            'cookies'      => true,
            'devices'      => true,
            'errors'       => true,
            'geoip'        => true,
            'languages'    => true,
            'paths'        => true,
            'path-queries' => true,
            'referers'     => true,
            'users'        => true,
            'user-agents'  => true,
        ]);

        $config->set('laravel-tracker.routes.ignore.names', [
            'admin::*',
        ]);
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
            $router->get('/', [
                'as' => 'home',
                function () {
                    return 'Tracked home route';
                }
            ]);

            $router->get('blog/posts/{post}', [
                'as' => 'blog::post.show',
                function ($post) {
                    return $post;
                }
            ]);

            $router->get('admin', [
                'as' => 'admin::home',
                function () {
                    return 'Tracked admin route';
                }
            ]);
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

    /**
     * Make a route.
     *
     * @param  array|string    $methods
     * @param  string          $uri
     * @param  \Closure|array  $action
     *
     * @return \Illuminate\Routing\Route
     */
    protected function makeRoute($methods, $uri, $action)
    {
        return new \Illuminate\Routing\Route($methods, $uri, $action);
    }
}
