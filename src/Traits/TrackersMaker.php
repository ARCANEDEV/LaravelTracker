<?php namespace Arcanedev\LaravelTracker\Traits;

use Arcanedev\LaravelTracker\Contracts;

/**
 * Class     TrackersMaker
 *
 * @package  Arcanedev\LaravelTracker\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait TrackersMaker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the cookie tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\CookieTracker
     */
    protected function getCookieTracker()
    {
        return $this->make(Contracts\Trackers\CookieTracker::class);
    }

    /**
     * Get the device tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\DeviceTracker
     */
    protected function getDeviceTracker()
    {
        return $this->make(Contracts\Trackers\DeviceTracker::class);
    }

    /**
     * Get the error tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\ErrorTracker
     */
    protected function getErrorTracker()
    {
        return $this->make(Contracts\Trackers\ErrorTracker::class);
    }

    /**
     * Get the geoip tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker
     */
    protected function getGeoIpTracker()
    {
        return $this->make(Contracts\Trackers\GeoIpTracker::class);
    }

    /**
     * Get the language tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\LanguageTracker
     */
    protected function getLanguageTracker()
    {
        return $this->make(Contracts\Trackers\LanguageTracker::class);
    }

    /**
     * Get the path tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\PathTracker
     */
    protected function getPathTracker()
    {
        return $this->make(Contracts\Trackers\PathTracker::class);
    }

    /**
     * Get the query tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\QueryTracker
     */
    protected function getQueryTracker()
    {
        return $this->make(Contracts\Trackers\QueryTracker::class);
    }

    /**
     * Get the referer tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\RefererTracker
     */
    protected function getRefererTracker()
    {
        return $this->make(Contracts\Trackers\RefererTracker::class);
    }

    /**
     * Get the visitor tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\VisitorTracker
     */
    protected function getVisitorTracker()
    {
        return $this->make(Contracts\Trackers\VisitorTracker::class);
    }

    /**
     * Get the visitor activity tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\VisitorActivityTracker
     */
    protected function getVisitorActivityTracker()
    {
        return $this->make(Contracts\Trackers\VisitorActivityTracker::class);
    }

    /**
     * Get the route tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\RouteTracker
     */
    protected function getRouteTracker()
    {
        return $this->make(Contracts\Trackers\RouteTracker::class);
    }

    /**
     * Get the user agent tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker
     */
    protected function getUserAgentTracker()
    {
        return $this->make(Contracts\Trackers\UserAgentTracker::class);
    }

    /**
     * Get the user tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\UserTracker
     */
    protected function getUserTracker()
    {
        return $this->make(Contracts\Trackers\UserTracker::class);
    }

    /**
     * Get the tracker instance.
     *
     * @param  string  $abstract
     *
     * @return mixed
     */
    private function make($abstract)
    {
        return $this->app()->make($abstract);
    }

    /**
     * Get the application instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    abstract protected function app();
}
