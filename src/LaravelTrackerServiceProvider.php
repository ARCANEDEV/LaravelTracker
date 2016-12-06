<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\Support\PackageServiceProvider;

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

        if ($this->app->runningInConsole())
            $this->app->register(Providers\CommandServiceProvider::class);

        $this->registerDetectors();
        $this->registerParsers();
        $this->registerTrackers();
        $this->registerMainTracker();
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
        $this->singleton(Contracts\Detectors\CrawlerDetector::class, function ($app) {
            /** @var \Illuminate\Http\Request $request */
            $request = $app['request'];
            $crawler = new \Jaybizzle\CrawlerDetect\CrawlerDetect(
                $request->headers->all(),
                $request->server('HTTP_USER_AGENT')
            );

            return new Detectors\CrawlerDetector($crawler);
        });

        $this->singleton(Contracts\Detectors\DeviceDetector::class, function ($app) {
            return new Detectors\DeviceDetector($app['agent']);
        });

        $this->singleton(
            Contracts\Detectors\GeoIpDetector::class,
            Detectors\GeoIpDetector::class
        );

        $this->singleton(Contracts\Detectors\LanguageDetector::class, function ($app) {
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

        $this->singleton(Contracts\Parsers\UserAgentParser::class, function ($app) {
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
        $this->singleton(Contracts\Trackers\CookieTracker::class,    Trackers\CookieTracker::class);
        $this->singleton(Contracts\Trackers\DeviceTracker::class,    Trackers\DeviceTracker::class);
        $this->singleton(Contracts\Trackers\GeoIpTracker::class,     Trackers\GeoIpTracker::class);
        $this->singleton(Contracts\Trackers\LanguageTracker::class,  Trackers\LanguageTracker::class);
        $this->singleton(Contracts\Trackers\PathTracker::class,      Trackers\PathTracker::class);
        $this->singleton(Contracts\Trackers\QueryTracker::class,     Trackers\QueryTracker::class);
        $this->singleton(Contracts\Trackers\RefererTracker::class,   Trackers\RefererTracker::class);
        $this->singleton(Contracts\Trackers\SessionTracker::class,   function ($app) {
            /** @var \Illuminate\Contracts\Foundation\Application $app */
            return new Trackers\SessionTracker($app['session.store']);
        });
        $this->singleton(Contracts\Trackers\UserAgentTracker::class, Trackers\UserAgentTracker::class);
        $this->singleton(Contracts\Trackers\UserTracker::class,      Trackers\UserTracker::class);

        // Register the trackers manager
        $this->singleton(Contracts\TrackingManager::class, TrackingManager::class);
    }

    /**
     * Register the main tracker.
     */
    private function registerMainTracker()
    {
        $this->singleton('arcanedev.tracker', Tracker::class);
        $this->bind(Contracts\Tracker::class, Tracker::class);
    }
}
