<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\LaravelTracker\Contracts\Detectors\CrawlerDetector;
use Arcanedev\LaravelTracker\Contracts\Tracker as TrackerContract;
use Arcanedev\LaravelTracker\Contracts\TrackingManager as TrackingManagerContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

/**
 * Class     Tracker
 *
 * @package  Arcanedev\LaravelTracker
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Tracker implements TrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The application container.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * The tracking manager.
     *
     * @var \Arcanedev\LaravelTracker\Contracts\TrackingManager
     */
    private $trackingManager;

    /**
     * @var bool
     */
    protected $enabled = false;

    /**
     * The current session data.
     *
     * @var array
     */
    protected $sessionData = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Tracker constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application         $app
     * @param  \Arcanedev\LaravelTracker\Contracts\TrackingManager  $trackingManager
     */
    public function __construct(Application $app, TrackingManagerContract $trackingManager)
    {
        $this->app             = $app;
        $this->trackingManager = $trackingManager;
        $this->enabled         = $this->getConfig('enabled', $this->enabled);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the config repository.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    private function config()
    {
        return $this->app['config'];
    }

    /**
     * Get the tracker config.
     *
     * @param  string      $key
     * @param  mixed|null  $default
     *
     * @return mixed
     */
    private function getConfig($key, $default = null)
    {
        return $this->config()->get("laravel-tracker.$key", $default);
    }

    /**
     * Set the request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return self
     */
    private function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the user agent parser.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser
     */
    public function getUserAgentParser()
    {
        return $this->trackingManager->getUserAgentTracker()->getUserAgentParser();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Start the tracking.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function track(Request $request)
    {
        if ($this->isEnabled()) {
            $this->setRequest($request);

            $activity = $this->getSessionActivityData();
        }
    }

    /**
     * Enable the tracker.
     */
    public function enable()
    {
        if ( ! $this->isEnabled())
            $this->enabled = true;
    }

    /**
     * Disable the tracker.
     */
    public function disable()
    {
        if ($this->isEnabled())
            $this->enabled = false;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the tracker is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the session log data.
     *
     * @return array
     */
    protected function getSessionActivityData()
    {
        return [
            'session_id'  => $this->getSessionId(),
            'method'      => $this->request->method(),
            'path_id'     => $this->getPathId(),
            'query_id'    => $this->getQueryId(),
            'referrer_id' => $this->getRefererId(),
            'is_ajax'     => $this->request->ajax(),
            'is_secure'   => $this->request->isSecure(),
            'is_json'     => $this->request->isJson(),
            'wants_json'  => $this->request->wantsJson(),
        ];
    }

    /**
     * Get the stored session id.
     *
     * @return int
     */
    protected function getSessionId()
    {
        return $this->trackingManager->trackSession(
            $this->makeSessionData()
        );
    }

    /**
     * @return array
     */
    protected function makeSessionData()
    {
        return $this->sessionData = $this->trackingManager->checkSessionData([
            'user_id'      => $this->getUserId(),
            'device_id'    => $this->getDeviceId(),
            'client_ip'    => $this->request->getClientIp(),
            'geoip_id'     => $this->getGeoIpId(),
            'agent_id'     => $this->getAgentId(),
            'referrer_id'  => $this->getRefererId(),
            'cookie_id'    => $this->getCookieId(),
            'language_id'  => $this->getLanguageId(),
            'is_robot'     => $this->isRobot(),

            // The key user_agent is not present in the sessions table, but it's internally used to check
            // if the user agent changed during a session.
            'user_agent'   => $this->getUserAgentParser()->getOriginalUserAgent(),
        ], $this->sessionData);
    }

    private function getPathId()
    {
        return 0;
    }

    private function getQueryId()
    {
        return 0;
    }

    /**
     * Get the user id.
     *
     * @return int|null
     */
    private function getUserId()
    {
        return $this->getConfig('tracking.users', false)
            ? (new TrackingManager)->trackUser()
            : null;
    }

    /**
     * Get the tracked device id.
     *
     * @return int|null
     */
    private function getDeviceId()
    {
        return $this->getConfig('tracking.devices', false)
            ? $this->trackingManager->trackDevice()
            : null;
    }

    /**
     * Get the tracked ip address ip.
     *
     * @return int|null
     */
    private function getGeoIpId()
    {
        return $this->getConfig('tracking.geoip', false)
            ? $this->trackingManager->trackGeoIp($this->request->getClientIp())
            : null;
    }

    /**
     * Get the tracked user agent id.
     *
     * @return int|null
     */
    private function getAgentId()
    {
        return $this->getConfig('tracking.user-agents', false)
            ? $this->trackingManager->trackUserAgent()
            : null;
    }

    /**
     * Get the tracked referer id.
     *
     * @return int|null
     */
    private function getRefererId()
    {
        return $this->getConfig('tracking.referers', false)
            ? $this->trackingManager->trackReferer($this->request->headers->get('referer'), $this->request->url())
            : null;
    }

    /**
     * Get the tracked cookie id.
     *
     * @return int|null
     */
    private function getCookieId()
    {
        return $this->getConfig('tracking.cookies', false)
            ? $this->trackingManager->trackCookie($this->request->cookie($this->getConfig('cookie.name')))
            : null;
    }

    /**
     * Get the tracked language id.
     *
     * @return int|null
     */
    private function getLanguageId()
    {
        return $this->getConfig('tracking.languages', false)
            ? $this->trackingManager->trackLanguage()
            : null;
    }

    /**
     * Check if the visitor is a robot.
     *
     * @return bool
     */
    protected function isRobot()
    {
        /** @var  \Arcanedev\LaravelTracker\Contracts\Detectors\CrawlerDetector  $crawler */
        $crawler = $this->app[CrawlerDetector::class];

        return $crawler->isRobot();
    }
}
