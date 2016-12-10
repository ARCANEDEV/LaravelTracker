<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\Support\PackageServiceProvider;
use Arcanedev\LaravelTracker\Contracts\Trackers as TrackerContracts;
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
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'laravel-tracker';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->register(Providers\PackagesServiceProvider::class);
        $this->app->register(Providers\EventServiceProvider::class);

        if ($this->app->runningInConsole())
            $this->app->register(Providers\CommandServiceProvider::class);

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

        $this->publishConfig();
        $this->publishMigrations();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'arcanedev.tracker',
            Contracts\Tracker::class,
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the detectors.
     */
    private function registerDetectors()
    {
        $this->singleton(Contracts\Detectors\CrawlerDetector::class, function (AppContract $app) {
            /** @var \Illuminate\Http\Request $request */
            $request = $app['request'];
            $crawler = new \Jaybizzle\CrawlerDetect\CrawlerDetect(
                $request->headers->all(),
                $request->server('HTTP_USER_AGENT')
            );

            return new Detectors\CrawlerDetector($crawler);
        });

        $this->singleton(Contracts\Detectors\DeviceDetector::class, function (AppContract $app) {
            return new Detectors\DeviceDetector($app['agent']);
        });

        $this->singleton(Contracts\Detectors\GeoIpDetector::class, Detectors\GeoIpDetector::class);

        $this->singleton(Contracts\Detectors\LanguageDetector::class, function (AppContract $app) {
            return new Detectors\LanguageDetector($app['agent']);
        });
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
                \UAParser\Parser::create(),
                $app->make('path.base')
            );
        });
    }

    /**
     * Register the trackers.
     */
    private function registerTrackers()
    {
        foreach ($this->getTrackers() as $abstract => $concrete) {
            $this->singleton($abstract, $concrete);
        }

        // Register the trackers manager
        $this->bind(Contracts\TrackingManager::class, TrackingManager::class);
    }

    /**
     * Register the main tracker.
     */
    private function registerMainTracker()
    {
        $this->singleton(Contracts\Tracker::class, Tracker::class);
        $this->bind('arcanedev.tracker', Contracts\Tracker::class);
    }

    /**
     * Get the trackers.
     *
     * @return array
     */
    private function getTrackers()
    {
        return [
            TrackerContracts\CookieTracker::class          => Trackers\CookieTracker::class,
            TrackerContracts\DeviceTracker::class          => Trackers\DeviceTracker::class,
            TrackerContracts\ErrorTracker::class           => Trackers\ErrorTracker::class,
            TrackerContracts\GeoIpTracker::class           => Trackers\GeoIpTracker::class,
            TrackerContracts\LanguageTracker::class        => Trackers\LanguageTracker::class,
            TrackerContracts\PathTracker::class            => Trackers\PathTracker::class,
            TrackerContracts\QueryTracker::class           => Trackers\QueryTracker::class,
            TrackerContracts\RefererTracker::class         => Trackers\RefererTracker::class,
            TrackerContracts\RouteTracker::class           => Trackers\RouteTracker::class,
            TrackerContracts\SessionTracker::class         => Trackers\SessionTracker::class,
            TrackerContracts\SessionActivityTracker::class => Trackers\SessionActivityTracker::class,
            TrackerContracts\UserAgentTracker::class       => Trackers\UserAgentTracker::class,
            TrackerContracts\UserTracker::class            => Trackers\UserTracker::class,
        ];
    }

    /**
     * Register the exception handler.
     */
    private function registerExceptionHandler()
    {
        $handler = $this->app[ExceptionHandlerContract::class];

        $this->app->singleton(ExceptionHandlerContract::class, function ($app) use ($handler) {
            return new Exceptions\Handler($app[Contracts\TrackingManager::class], $handler);
        });
    }
}
