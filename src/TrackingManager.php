<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\LaravelTracker\Contracts\TrackingManager as TrackingManagerContract;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class     TrackingManager
 *
 * @package  Arcanedev\LaravelTracker
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrackingManager implements TrackingManagerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Illuminate\Contracts\Foundation\Application  */
    private $app;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * TrackingManager constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the cookie tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\CookieTracker
     */
    private function getCookieTracker()
    {
        return $this->getTracker(Contracts\Trackers\CookieTracker::class);
    }

    /**
     * Get the device tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\DeviceTracker
     */
    private function getDeviceTracker()
    {
        return $this->getTracker(Contracts\Trackers\DeviceTracker::class);
    }

    /**
     * Get the error tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\ErrorTracker
     */
    private function getErrorTracker()
    {
        return $this->getTracker(Contracts\Trackers\ErrorTracker::class);
    }

    /**
     * Get the geoip tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker
     */
    private function getGeoIpTracker()
    {
        return $this->getTracker(Contracts\Trackers\GeoIpTracker::class);
    }

    /**
     * Get the language tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\LanguageTracker
     */
    private function getLanguageTracker()
    {
        return $this->getTracker(Contracts\Trackers\LanguageTracker::class);
    }

    /**
     * Get the path tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\PathTracker
     */
    private function getPathTracker()
    {
        return $this->getTracker(Contracts\Trackers\PathTracker::class);
    }

    /**
     * Get the query tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\QueryTracker
     */
    private function getQueryTracker()
    {
        return $this->getTracker(Contracts\Trackers\QueryTracker::class);
    }

    /**
     * Get the referer tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\RefererTracker
     */
    private function getRefererTracker()
    {
        return $this->getTracker(Contracts\Trackers\RefererTracker::class);
    }

    /**
     * Get the session tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\SessionTracker
     */
    private function getSessionTracker()
    {
        return $this->getTracker(Contracts\Trackers\SessionTracker::class);
    }

    /**
     * Get the session activity tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\SessionActivityTracker
     */
    private function getSessionActivityTracker()
    {
        return $this->getTracker(Contracts\Trackers\SessionActivityTracker::class);
    }

    /**
     * Get the route tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\RouteTracker
     */
    public function getRouteTracker()
    {
        return $this->getTracker(Contracts\Trackers\RouteTracker::class);
    }

    /**
     * Get the user agent tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker
     */
    public function getUserAgentTracker()
    {
        return $this->getTracker(Contracts\Trackers\UserAgentTracker::class);
    }

    /**
     * Get the user tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\UserTracker
     */
    private function getUserTracker()
    {
        return $this->getTracker(Contracts\Trackers\UserTracker::class);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the exception error.
     *
     * @param  \Exception  $exception
     *
     * @return int
     */
    public function trackException(\Exception $exception)
    {
        return $this->getErrorTracker()->track($exception);
    }

    /**
     * Track the path.
     *
     * @param  string  $path
     *
     * @return int
     */
    public function trackPath($path)
    {
        return $this->getPathTracker()->track($path);
    }

    /**
     * Track the query.
     *
     * @param  array  $queries
     *
     * @return int|null
     */
    public function trackQuery(array $queries)
    {
        return $this->getQueryTracker()->track($queries);
    }

    /**
     * Track the user.
     *
     * @return int|null
     */
    public function trackUser()
    {
        return $this->getUserTracker()->track();
    }

    /**
     * Track the device.
     *
     * @return int
     */
    public function trackDevice()
    {
        return $this->getDeviceTracker()->track();
    }

    /**
     * Track the ip address.
     *
     * @param  string  $ipAddress
     *
     * @return int|null
     */
    public function trackGeoIp($ipAddress)
    {
        return $this->getGeoIpTracker()->track($ipAddress);
    }

    /**
     * Track the user agent.
     *
     * @return int
     */
    public function trackUserAgent()
    {
        return $this->getUserAgentTracker()->track();
    }

    /**
     * Track the referer.
     *
     * @param  string  $refererUrl
     * @param  string  $pageUrl
     *
     * @return int|null
     */
    public function trackReferer($refererUrl, $pageUrl)
    {
        return $this->getRefererTracker()->track($refererUrl, $pageUrl);
    }

    /**
     * Track the cookie.
     *
     * @param  mixed  $cookie
     *
     * @return int|null
     */
    public function trackCookie($cookie)
    {
        return $this->getCookieTracker()->track($cookie);
    }

    /**
     * Track the language.
     *
     * @return int
     */
    public function trackLanguage()
    {
        return $this->getLanguageTracker()->track();
    }

    /**
     * Track the session.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function trackSession(array $data)
    {
        return $this->getSessionTracker()->track($data);
    }

    /**
     * Track the session activity.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function trackActivity(array $data)
    {
        return $this->getSessionActivityTracker()->track($data);
    }

    /**
     * Check the session data.
     *
     * @param  array  $currentData
     * @param  array  $newData
     *
     * @return array
     */
    public function checkSessionData(array $currentData, array $newData)
    {
        return $this->getSessionTracker()->checkData($currentData, $newData);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the tracker instance.
     *
     * @param  string  $abstract
     *
     * @return mixed
     */
    private function getTracker($abstract)
    {
        return $this->app->make($abstract);
    }
}
