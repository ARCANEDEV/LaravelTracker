<?php namespace Arcanedev\LaravelTracker\Contracts;

/**
 * Interface  TrackingManager
 *
 * @package   Arcanedev\LaravelTracker\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TrackingManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the user agent tracker.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker
     */
    public function getUserAgentTracker();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the path.
     *
     * @param  string  $path
     *
     * @return int
     */
    public function trackPath($path);

    /**
     * Track the query.
     *
     * @param  array  $queries
     *
     * @return int|null
     */
    public function trackQuery(array $queries);

    /**
     * Track the user.
     *
     * @return int|null
     */
    public function trackUser();

    /**
     * Track the device.
     *
     * @return int
     */
    public function trackDevice();

    /**
     * Track the ip address.
     *
     * @param  string  $ipAddress
     *
     * @return int|null
     */
    public function trackGeoIp($ipAddress);

    /**
     * Track the user agent.
     *
     * @return int
     */
    public function trackUserAgent();

    /**
     * Track the referer.
     *
     * @param  string  $refererUrl
     * @param  string  $pageUrl
     *
     * @return int|null
     */
    public function trackReferer($refererUrl, $pageUrl);

    /**
     * Track the cookie.
     *
     * @param  mixed  $cookie
     *
     * @return int|null
     */
    public function trackCookie($cookie);

    /**
     * Track the language.
     *
     * @return int
     */
    public function trackLanguage();

    /**
     * Track the session.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function trackSession(array $data);

    /**
     * Check the session data.
     *
     * @param  array  $data
     * @param  array  $currentData
     *
     * @return array
     */
    public function checkSessionData(array $data, array $currentData);
}
