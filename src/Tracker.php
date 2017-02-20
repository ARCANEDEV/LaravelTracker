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
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */
    use Traits\TrackersMaker;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
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
     * The current visitor data.
     *
     * @var array
     */
    protected $visitorData = [
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
     * The current visitor activity data.
     *
     * @var array
     */
    protected $visitorActivityData = [
        'visitor_id'    => null,
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

    /**
     * Indicates if migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */
    /**
     * Get the application instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    protected function app()
    {
        return $this->app;
    }

    /**
     * Get the config repository.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    private function config()
    {
        return $this->make('config');
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
        $this->mergeVisitorActivityData([
            'method'      => $request->method(),
            'is_ajax'     => $request->ajax(),
            'is_secure'   => $request->isSecure(),
            'is_json'     => $request->isJson(),
            'wants_json'  => $request->wantsJson(),
        ]);

        $this->request = $request;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Start the tracking.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function trackRequest(Request $request)
    {
        if ($this->isEnabled()) {
            $this->setRequest($request);

            $this->mergeVisitorActivityData([
                'visitor_id' => $this->getVisitorId(),
                'path_id'    => $this->getPathId(),
                'query_id'   => $this->getQueryId(),
                'referer_id' => $this->getRefererId(),
            ]);

            $id = $this->getVisitorActivityTracker()->track($this->visitorActivityData);
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
            $this->mergeVisitorActivityData([
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

        $this->mergeVisitorActivityData(['error_id' => $id]);
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

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Merge visitor data.
     *
     * @param  array  $data
     *
     * @return self
     */
    private function mergeVisitorData(array $data)
    {
        $this->visitorData = array_merge($this->visitorData, $data);

        return $this;
    }

    /**
     * Merge visitor activity data.
     *
     * @param  array  $data
     *
     * @return self
     */
    private function mergeVisitorActivityData(array $data)
    {
        $this->visitorActivityData = array_merge($this->visitorActivityData, $data);

        return $this;
    }

    /**
     * Get the stored visitor id.
     *
     * @return int
     */
    private function getVisitorId()
    {
        $tracker = $this->getVisitorTracker();
        $data    = $tracker->checkData($this->visitorData, [
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

        $this->mergeVisitorData($data);

        return $tracker->track($this->visitorData);
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
        $crawler = $this->make(Contracts\Detectors\CrawlerDetector::class);

        return $crawler->isRobot();
    }
}
