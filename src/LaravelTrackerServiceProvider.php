<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\Support\PackageServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Contracts\Foundation\Application as AppContract;

/**
 * Class     LaravelTrackerServiceProvider
 *
 * @package  Arcanedev\LaravelTracker
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LaravelTrackerServiceProvider extends PackageServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'laravel-tracker';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerConfig();
        $this->registerProviders([
            Providers\PackagesServiceProvider::class,
            Providers\EventServiceProvider::class,
        ]);
        $this->registerConsoleServiceProvider(Providers\CommandServiceProvider::class);

        $this->registerDetectors();
        $this->registerParsers();
        $this->registerTrackers();
        $this->registerMainTracker();
        $this->registerExceptionHandler();
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        $this->registerModelsBindings();

        // Publishes
        $this->publishConfig();

        Tracker::$runsMigrations ? $this->loadMigrations() : $this->publishMigrations();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contracts\Tracker::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Binding the models with the contracts.
     */
    private function registerModelsBindings()
    {
        foreach (Support\BindingManager::getModelsBindings() as $key => $contract) {
            $this->bind($contract, $this->config()->get("laravel-tracker.models.$key"));
        }
    }

    /**
     * Register the detectors.
     */
    private function registerDetectors()
    {
        $this->singleton(Contracts\Detectors\CrawlerDetector::class, function (AppContract $app) {
            /** @var  \Illuminate\Http\Request  $request */
            $request = $app['request'];

            return new Detectors\CrawlerDetector(
                new \Jaybizzle\CrawlerDetect\CrawlerDetect(
                    $request->headers->all(),
                    $request->server('HTTP_USER_AGENT')
                )
            );
        });

        $this->singleton(Contracts\Detectors\DeviceDetector::class,   Detectors\DeviceDetector::class);
        $this->singleton(Contracts\Detectors\GeoIpDetector::class,    Detectors\GeoIpDetector::class);
        $this->singleton(Contracts\Detectors\LanguageDetector::class, Detectors\LanguageDetector::class);
    }

    /**
     * Register the parsers.
     */
    private function registerParsers()
    {
        $this->singleton(Contracts\Parsers\RefererParser::class, function () {
            return new Parsers\RefererParser(
                new \Snowplow\RefererParser\Parser
            );
        });

        $this->singleton(Contracts\Parsers\UserAgentParser::class, function (AppContract $app) {
            return new Parsers\UserAgentParser(
                \UAParser\Parser::create(), $app->make('path.base')
            );
        });
    }

    /**
     * Register the trackers.
     */
    private function registerTrackers()
    {
        foreach (Support\BindingManager::getTrackersBindings() as $abstract => $concrete) {
            $this->singleton($abstract, $concrete);
        }
    }

    /**
     * Register the main tracker.
     */
    private function registerMainTracker()
    {
        $this->singleton(Contracts\Tracker::class, Tracker::class);
    }

    /**
     * Register the exception handler.
     */
    private function registerExceptionHandler()
    {
        $handler = $this->app[ExceptionHandlerContract::class];

        $this->singleton(ExceptionHandlerContract::class, function ($app) use ($handler) {
            return new Exceptions\Handler($app[Contracts\Tracker::class], $handler);
        });
    }
}
