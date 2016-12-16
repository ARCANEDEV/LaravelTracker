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
        $this->bindModels();

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
     * Binding the models with the contracts.
     */
    private function bindModels()
    {
        foreach ($this->getModelsBindings() as $key => $contract) {
            $this->bind($contract, $this->config()->get("laravel-tracker.models.$key"));
        }
    }

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
        foreach ($this->getTrackersBindings() as $abstract => $concrete) {
            $this->singleton($abstract, $concrete);
        }
    }

    /**
     * Register the main tracker.
     */
    private function registerMainTracker()
    {
        $this->singleton(Contracts\Tracker::class, Tracker::class);
        $this->singleton('arcanedev.tracker', Contracts\Tracker::class);
    }

    /**
     * Register the exception handler.
     */
    private function registerExceptionHandler()
    {
        $handler = $this->app[ExceptionHandlerContract::class];

        $this->app->singleton(ExceptionHandlerContract::class, function ($app) use ($handler) {
            return new Exceptions\Handler($app[Contracts\Tracker::class], $handler);
        });
    }

    /**
     * Get the models bindings.
     *
     * @return array
     */
    private function getModelsBindings()
    {
        return [
            Models\AbstractModel::MODEL_AGENT                => Contracts\Models\Agent::class,
            Models\AbstractModel::MODEL_COOKIE               => Contracts\Models\Cookie::class,
            Models\AbstractModel::MODEL_DEVICE               => Contracts\Models\Device::class,
            Models\AbstractModel::MODEL_DOMAIN               => Contracts\Models\Domain::class,
            Models\AbstractModel::MODEL_ERROR                => Contracts\Models\Error::class,
            Models\AbstractModel::MODEL_GEOIP                => Contracts\Models\GeoIp::class,
            Models\AbstractModel::MODEL_LANGUAGE             => Contracts\Models\Language::class,
            Models\AbstractModel::MODEL_PATH                 => Contracts\Models\Path::class,
            Models\AbstractModel::MODEL_QUERY                => Contracts\Models\Query::class,
            Models\AbstractModel::MODEL_REFERER              => Contracts\Models\Referer::class,
            Models\AbstractModel::MODEL_REFERER_SEARCH_TERM  => Contracts\Models\RefererSearchTerm::class,
            Models\AbstractModel::MODEL_ROUTE                => Contracts\Models\Route::class,
            Models\AbstractModel::MODEL_ROUTE_PATH           => Contracts\Models\RoutePath::class,
            Models\AbstractModel::MODEL_ROUTE_PATH_PARAMETER => Contracts\Models\RoutePathParameter::class,
            Models\AbstractModel::MODEL_SESSION              => Contracts\Models\Session::class,
            Models\AbstractModel::MODEL_SESSION_ACTIVITY     => Contracts\Models\SessionActivity::class,
            Models\AbstractModel::MODEL_USER                 => Contracts\Models\User::class,
        ];
    }

    /**
     * Get the trackers bindings.
     *
     * @return array
     */
    private function getTrackersBindings()
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
}
