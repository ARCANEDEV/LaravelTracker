<?php namespace Arcanedev\LaravelTracker;

/**
 * Class     TrackingManager
 *
 * @package  Arcanedev\LaravelTracker
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrackingManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the user agent tracker.
     *
     * @return Trackers\UserAgentTracker
     */
    public function getUserAgentTracker()
    {
        return new Trackers\UserAgentTracker;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the user.
     *
     * @return int|null
     */
    public function trackUser()
    {
        return (new Trackers\UserTracker)->track();
    }

    /**
     * Track the device.
     *
     * @return int
     */
    public function trackDevice()
    {
        return (new Trackers\DeviceTracker)->track();
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
        return (new Trackers\GeoIpTracker)->track($ipAddress);
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
        return (new Trackers\RefererTracker)->track($refererUrl, $pageUrl);
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
        return (new Trackers\CookieTracker)->track($cookie);
    }

    /**
     * Track the language.
     *
     * @return int
     */
    public function trackLanguage()
    {
        return (new Trackers\LanguageTracker)->track();
    }

    /**
     * Track the session.
     *
     * @param  array  $data
     */
    public function trackSession(array $data)
    {
        return (new Trackers\SessionTracker)->track($data);
    }

    /**
     * Check the session data.
     *
     * @param  array  $data
     * @param  array  $currentData
     *
     * @return array
     */
    public function checkSessionData(array $data, array $currentData)
    {
        return (new Trackers\SessionTracker)->checkData($data, $currentData);
    }
}
