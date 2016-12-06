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

            $id = $this->trackingManager->trackActivity(
                $activity = $this->getSessionActivityData()
            );
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
    private function getSessionId()
    {
        $this->sessionData = $this->trackingManager->checkSessionData([
            'user_id'      => $this->getUserId(),
            'device_id'    => $this->getDeviceId(),
            'client_ip'    => $this->request->getClientIp(),
            'geoip_id'     => $this->getGeoIpId(),
            'agent_id'     => $this->getAgentId(),
            'referrer_id'  => $this->getRefererId(),
            'cookie_id'    => $this->getCookieId(),
            'language_id'  => $this->getLanguageId(),
            'is_robot'     => $this->isRobot(),
            // The key `user_agent` is not present in the sessions table, but it's internally used to check
            // if the user agent changed during a session.
            'user_agent'   => $this->getUserAgentParser()->getOriginalUserAgent(),
        ], $this->sessionData);

        return $this->trackingManager->trackSession($this->sessionData);
    }

    /**
     * Track the path.
     *
     * @return int|null
     */
    private function getPathId()
    {
        return $this->trackIfEnabled('paths', function () {
            return $this->trackingManager->trackPath(
                $this->request->path()
            );
        });
    }

    /**
     * Track the query.
     *
     * @return int|null
     */
    private function getQueryId()
    {
        return $this->trackIfEnabled('path-queries', function () {
            return $this->trackingManager->trackQuery(
                $this->request->query()
            );
        });
    }

    /**
     * Get the user id.
     *
     * @return int|null
     */
    private function getUserId()
    {
        return $this->trackIfEnabled('users', function () {
            return $this->trackingManager->trackUser();
        });
    }

    /**
     * Get the tracked device id.
     *
     * @return int|null
     */
    private function getDeviceId()
    {
        return $this->trackIfEnabled('devices', function () {
            return $this->trackingManager->trackDevice();
        });
    }

    /**
     * Get the tracked ip address ip.
     *
     * @return int|null
     */
    private function getGeoIpId()
    {
        return $this->trackIfEnabled('geoip', function () {
            return $this->trackingManager->trackGeoIp(
                $this->request->getClientIp()
            );
        });
    }

    /**
     * Get the tracked user agent id.
     *
     * @return int|null
     */
    private function getAgentId()
    {
        return $this->trackIfEnabled('user-agents', function () {
            return $this->trackingManager->trackUserAgent();
        });
    }

    /**
     * Get the tracked referer id.
     *
     * @return int|null
     */
    private function getRefererId()
    {
        return $this->trackIfEnabled('referers', function () {
            return $this->trackingManager->trackReferer(
                $this->request->headers->get('referer'),
                $this->request->url()
            );
        });
    }

    /**
     * Get the tracked cookie id.
     *
     * @return int|null
     */
    private function getCookieId()
    {
        return $this->trackIfEnabled('cookies', function () {
            return $this->trackingManager->trackCookie(
                $this->request->cookie($this->getConfig('cookie.name'))
            );
        });
    }

    /**
     * Get the tracked language id.
     *
     * @return int|null
     */
    private function getLanguageId()
    {
        return $this->trackIfEnabled('languages', function () {
            return $this->trackingManager->trackLanguage();
        });
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

    /**
     * Track the trackable if enabled.
     *
     * @param  string      $key
     * @param  \Closure    $callback
     * @param  mixed|null  $default
     *
     * @return mixed
     */
    private function trackIfEnabled($key, \Closure $callback, $default = null)
    {
        return $this->getConfig("tracking.$key", false) ? $callback() : $default;
    }
}
