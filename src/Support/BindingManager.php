<?php namespace Arcanedev\LaravelTracker\Support;

use Arcanedev\LaravelTracker\Contracts\Models as ModelContracts;
use Arcanedev\LaravelTracker\Contracts\Trackers as TrackerContracts;
use Arcanedev\LaravelTracker\Trackers;

/**
 * Class     BindingManager
 *
 * @package  Arcanedev\LaravelTracker\Support
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class BindingManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const MODEL_AGENT                = 'agent';
    const MODEL_COOKIE               = 'cookie';
    const MODEL_DEVICE               = 'device';
    const MODEL_DOMAIN               = 'domain';
    const MODEL_ERROR                = 'error';
    const MODEL_GEOIP                = 'geoip';
    const MODEL_LANGUAGE             = 'language';
    const MODEL_PATH                 = 'path';
    const MODEL_QUERY                = 'query';
    const MODEL_REFERER              = 'referer';
    const MODEL_REFERER_SEARCH_TERM  = 'referer-search-term';
    const MODEL_ROUTE                = 'route';
    const MODEL_ROUTE_PATH           = 'route-path';
    const MODEL_ROUTE_PATH_PARAMETER = 'route-path-parameter';
    const MODEL_SESSION              = 'session';
    const MODEL_SESSION_ACTIVITY     = 'session-activity';
    const MODEL_USER                 = 'user';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Models bindings.
     *
     * @var array
     */
    protected static $models = [
        self::MODEL_AGENT                => ModelContracts\Agent::class,
        self::MODEL_COOKIE               => ModelContracts\Cookie::class,
        self::MODEL_DEVICE               => ModelContracts\Device::class,
        self::MODEL_DOMAIN               => ModelContracts\Domain::class,
        self::MODEL_ERROR                => ModelContracts\Error::class,
        self::MODEL_GEOIP                => ModelContracts\GeoIp::class,
        self::MODEL_LANGUAGE             => ModelContracts\Language::class,
        self::MODEL_PATH                 => ModelContracts\Path::class,
        self::MODEL_QUERY                => ModelContracts\Query::class,
        self::MODEL_REFERER              => ModelContracts\Referer::class,
        self::MODEL_REFERER_SEARCH_TERM  => ModelContracts\RefererSearchTerm::class,
        self::MODEL_ROUTE                => ModelContracts\Route::class,
        self::MODEL_ROUTE_PATH           => ModelContracts\RoutePath::class,
        self::MODEL_ROUTE_PATH_PARAMETER => ModelContracts\RoutePathParameter::class,
        self::MODEL_SESSION              => ModelContracts\Session::class,
        self::MODEL_SESSION_ACTIVITY     => ModelContracts\SessionActivity::class,
        self::MODEL_USER                 => ModelContracts\User::class,
    ];

    /**
     * Trackers bindings.
     *
     * @var array
     */
    protected static $trackers = [
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

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the models bindings.
     *
     * @return array
     */
    public static function getModelsBindings()
    {
        return self::$models;
    }

    /**
     * Get the trackers bindings.
     *
     * @return array
     */
    public static function getTrackersBindings()
    {
        return self::$trackers;
    }
}
