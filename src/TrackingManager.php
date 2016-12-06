<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\LaravelTracker\Contracts\TrackingManager as TrackingManagerContract;

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
    /** @var Contracts\Trackers\CookieTracker */
    private $cookieTracker;

    /** @var Contracts\Trackers\DeviceTracker */
    private $deviceTracker;

    /** @var Contracts\Trackers\GeoIpTracker */
    private $geoIpTracker;

    /** @var Contracts\Trackers\LanguageTracker */
    private $languageTracker;

    /** @var Contracts\Trackers\PathTracker */
    private $pathTracker;

    /** @var Contracts\Trackers\RefererTracker */
    private $refererTracker;

    /** @var Contracts\Trackers\SessionTracker */
    private $sessionTracker;

    /** @var Contracts\Trackers\UserAgentTracker */
    private $userAgentTracker;

    /** @var Contracts\Trackers\UserTracker */
    private $userTracker;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * TrackingManager constructor.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\CookieTracker     $cookieTracker
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\DeviceTracker     $deviceTracker
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker      $geoIpTracker
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\LanguageTracker   $languageTracker
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\PathTracker       $pathTracker
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\RefererTracker    $refererTracker
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\SessionTracker    $sessionTracker
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker  $userAgentTracker
     * @param  \Arcanedev\LaravelTracker\Contracts\Trackers\UserTracker       $userTracker
     */
    public function __construct(
        Contracts\Trackers\CookieTracker $cookieTracker,
        Contracts\Trackers\DeviceTracker $deviceTracker,
        Contracts\Trackers\GeoIpTracker $geoIpTracker,
        Contracts\Trackers\LanguageTracker $languageTracker,
        Contracts\Trackers\PathTracker $pathTracker,
        Contracts\Trackers\RefererTracker $refererTracker,
        Contracts\Trackers\SessionTracker $sessionTracker,
        Contracts\Trackers\UserAgentTracker $userAgentTracker,
        Contracts\Trackers\UserTracker $userTracker
    ) {
        $this->cookieTracker    = $cookieTracker;
        $this->deviceTracker    = $deviceTracker;
        $this->geoIpTracker     = $geoIpTracker;
        $this->languageTracker  = $languageTracker;
        $this->pathTracker      = $pathTracker;
        $this->refererTracker   = $refererTracker;
        $this->sessionTracker   = $sessionTracker;
        $this->userAgentTracker = $userAgentTracker;
        $this->userTracker      = $userTracker;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the user agent tracker.
     *
     * @return Contracts\Trackers\UserAgentTracker
     */
    public function getUserAgentTracker()
    {
        return $this->userAgentTracker;
    }

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
    public function trackPath($path)
    {
        return $this->pathTracker->track($path);
    }

    /**
     * Track the user.
     *
     * @return int|null
     */
    public function trackUser()
    {
        return $this->userTracker->track();
    }

    /**
     * Track the device.
     *
     * @return int
     */
    public function trackDevice()
    {
        return $this->deviceTracker->track();
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
        return $this->geoIpTracker->track($ipAddress);
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
        return $this->refererTracker->track($refererUrl, $pageUrl);
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
        return $this->cookieTracker->track($cookie);
    }

    /**
     * Track the language.
     *
     * @return int
     */
    public function trackLanguage()
    {
        return $this->languageTracker->track();
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
        return $this->sessionTracker->track($data);
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
        return $this->sessionTracker->checkData($data, $currentData);
    }
}
