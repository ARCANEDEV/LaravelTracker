<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\LaravelTracker\Contracts\Tracker as TrackerContract;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

/**
 * Class     Tracker
 *
 * @package  Arcanedev\LaravelTracker
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Tracker implements TrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use Traits\TrackersMaker;

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
     * Tracking enabled status.
     *
     * @var  bool
     */
    protected $enabled = false;

    /**
     * The current session data.
     *
     * @var array
     */
    protected $sessionData = [
        'user_id'     => null,
        'device_id'   => null,
        'agent_id'    => null,
        'geoip_id'    => null,
        'referer_id'  => null,
        'cookie_id'   => null,
        'language_id' => null,
        'client_ip'   => '',
        'is_robot'    => false,
        'user_agent'  => '',
    ];

    /**
     * The current session activity data.
     *
     * @var array
     */
    protected $sessionActivityData = [
        'session_id'    => null,
        'path_id'       => null,
        'query_id'      => null,
        'referer_id'    => null,
        'route_path_id' => null,
        'error_id'      => null,
        'method'        => '',
        'is_ajax'       => false,
        'is_secure'     => false,
        'is_json'       => false,
        'wants_json'    => false,
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Tracker constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        $this->app     = $app;
        $this->enabled = $this->getConfig('enabled', $this->enabled);
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
        $this->mergeSessionActivityData([
            'method'      => $request->method(),
            'is_ajax'     => $request->ajax(),
            'is_secure'   => $request->isSecure(),
            'is_json'     => $request->isJson(),
            'wants_json'  => $request->wantsJson(),
        ]);

        $this->request = $request;

        return $this;
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
    public function trackRequest(Request $request)
    {
        if ($this->isEnabled()) {
            $this->setRequest($request);

            $this->mergeSessionActivityData([
                'session_id' => $this->getSessionId(),
                'path_id'    => $this->getPathId(),
                'query_id'   => $this->getQueryId(),
                'referer_id' => $this->getRefererId(),
            ]);

            $id = $this->getSessionActivityTracker()->track($this->sessionActivityData);
        }
    }

    /**
     * Track the matched route.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request   $request
     */
    public function trackMatchedRoute(Route $route, Request $request)
    {
        if ( ! $this->isEnabled()) return;

        $tracker = $this->getRouteTracker();

        if ($tracker->isTrackable($route)) {
            $this->mergeSessionActivityData([
                'route_path_id' => $tracker->track($route, $request),
            ]);
        }
        else
            $this->disable();
    }

    /**
     * Track the exception.
     *
     * @param  \Exception  $exception
     */
    public function trackException(Exception $exception)
    {
        $id = $this->trackIfEnabled('errors', function () use ($exception) {
            $this->getErrorTracker()->track($exception);
        });

        $this->mergeSessionActivityData(['error_id' => $id]);
    }

    /**
     * Enable the tracking.
     */
    public function enable()
    {
        if ( ! $this->isEnabled()) $this->enabled = true;
    }

    /**
     * Disable the tracking.
     */
    public function disable()
    {
        if ($this->isEnabled()) $this->enabled = false;
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
        return $this->isEnabled()
            ? ($this->getConfig("tracking.$key", false) ? $callback() : $default)
            : $default;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Merge session data.
     *
     * @param  array  $data
     *
     * @return self
     */
    private function mergeSessionData(array $data)
    {
        $this->sessionData = array_merge($this->sessionData, $data);

        return $this;
    }

    /**
     * Merge session activity data.
     *
     * @param  array  $data
     *
     * @return self
     */
    private function mergeSessionActivityData(array $data)
    {
        $this->sessionActivityData = array_merge($this->sessionActivityData, $data);

        return $this;
    }

    /**
     * Get the stored session id.
     *
     * @return int
     */
    private function getSessionId()
    {
        $tracker     = $this->getSessionTracker();
        $sessionData = $tracker->checkData($this->sessionData, [
            'user_id'     => $this->getUserId(),
            'device_id'   => $this->getDeviceId(),
            'client_ip'   => $this->request->getClientIp(),
            'geoip_id'    => $this->getGeoIpId(),
            'agent_id'    => $this->getAgentId(),
            'referer_id'  => $this->getRefererId(),
            'cookie_id'   => $this->getCookieId(),
            'language_id' => $this->getLanguageId(),
            'is_robot'    => $this->isRobot(),
            'user_agent'  => $this->getUserAgentTracker()->getUserAgentParser()->getOriginalUserAgent(),
        ]);

        $this->mergeSessionData($sessionData);

        return $tracker->track($this->sessionData);
    }

    /**
     * Track the path.
     *
     * @return int|null
     */
    private function getPathId()
    {
        return $this->trackIfEnabled('paths', function () {
            return $this->getPathTracker()->track(
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
            return $this->getQueryTracker()->track(
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
            return $this->getUserTracker()->track();
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
            return $this->getDeviceTracker()->track();
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
            return $this->getGeoIpTracker()->track(
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
            return $this->getUserAgentTracker()->track();
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
            return $this->getRefererTracker()->track(
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
            return ! is_null($name = $this->getConfig('cookie.name'))
                ? $this->getCookieTracker()->track($this->request->cookie($name))
                : null;
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
            return $this->getLanguageTracker()->track();
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
        $crawler = $this->make(\Arcanedev\LaravelTracker\Contracts\Detectors\CrawlerDetector::class);

        return $crawler->isRobot();
    }
}
