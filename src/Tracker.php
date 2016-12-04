<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\LaravelTracker\Contracts\Detectors\CrawlerDetector;
use Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector;
use Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector;
use Arcanedev\LaravelTracker\Contracts\Detectors\LanguageDetector;
use Arcanedev\LaravelTracker\Contracts\Detectors\UserDetector;
use Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser;
use Arcanedev\LaravelTracker\Contracts\Tracker as TrackerContract;
use Arcanedev\LaravelTracker\Parsers\RefererParser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
     * @var bool
     */
    protected $enabled = false;

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
            'session_id'  => $this->getSessionId(true),
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
     * @return array
     */
    protected function makeSessionData()
    {
        return [
            'user_id'      => $this->getUserId(),
            'device_id'    => $this->getDeviceId(),
            'client_ip'    => $this->request->getClientIp(),
            'geoip_id'     => $this->getGeoIpId(),
            'agent_id'     => $this->getAgentId(),
            'referrer_id'  => $this->getRefererId(),
            'cookie_id'    => $this->getCookieId(),
            'language_id'  => $this->getLanguageId(),
            'is_robot'     => $this->isRobot(),

            // The key user_agent is not present in the sessions table, but
            // it's internally used to check if the user agent changed
            // during a session.
            'user_agent'   => $this->getCurrentUserAgent(),
        ];
    }

    protected function getSessionId($updateLastActivity = false)
    {
        dd($this->makeSessionData());
        return 0;
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
            ? $this->app[UserDetector::class]->getCurrentUserId()
            : null;
    }

    /**
     * Get the device id.
     *
     * @return int|null
     */
    private function getDeviceId()
    {
        if ($this->getConfig('tracking.devices', false)) {

            $data  = $this->getCurrentDeviceProperties();
            $model = Models\Device::firstOrCreate($data, $data);

            return $model->id;
        }

        return null;
    }

    /**
     * Get the current device properties.
     *
     * @return array
     */
    public function getCurrentDeviceProperties()
    {
        if ($properties = $this->app[DeviceDetector::class]->detect()) {
            $ua = $this->getUserAgentParser();

            $properties['platform']         = $ua->getOperatingSystemFamily();
            $properties['platform_version'] = $ua->getOperatingSystemVersion();
        }

        return $properties;
    }

    private function getGeoIpId()
    {
        if ($this->getConfig('tracking.geoip', false)) {
            $data = $this->app[GeoIpDetector::class]->search(
                $this->request->getClientIp()
            );

            if ($data) {
                $model = Models\GeoIp::firstOrCreate(Arr::only($data, ['latitude', 'longitude']), $data);

                return $model->id;
            }
        }

        return null;
    }

    private function getAgentId()
    {
        if ($this->getConfig('tracking.user-agents', false)) {
            $data  = $this->getCurrentUserAgentData();
            $model = Models\Agent::firstOrCreate($data, $data);

            return $model->id;
        }

        return null;
    }

    private function getRefererId()
    {
        if ($this->getConfig('tracking.referers', false)) {
            /** @var  \Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser  $parser */
            $parser = $this->app[\Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser::class];
            $parsed = $parser->parseUrl($this->request->headers->get('referer'));

            if ($parsed) {
                $domainId   = $this->getDomainId($parsed['domain']);
                $attributes = [
                    'url'               => $parsed['url'],
                    'host'              => $parsed['host'],
                    'domain_id'         => $domainId,
                    'medium'            => null,
                    'source'            => null,
                    'search_terms_hash' => null,
                ];

                $parsed = $parser->parse($parsed['url'], $this->request->url());

                if ($parsed->isKnown()) {
                    $attributes['medium']            = $parsed->getMedium();
                    $attributes['source']            = $parsed->getSource();
                    $attributes['search_terms_hash'] = sha1($parsed->getSearchTerm());
                }

                $referer = Models\Referer::firstOrCreate(
                    Arr::only($attributes, ['url', 'search_terms_hash']),
                    $attributes
                );

                if ($parsed->isKnown()) {
                    $this->storeSearchTerms($referer->id, $parsed->getSearchTerm());
                }

                return $referer->id;
            }
        }

        return null;
    }

    /**
     * Get the domain id.
     *
     * @param  string  $name
     *
     * @return int
     */
    private function getDomainId($name)
    {
        $data = compact('name');

        return Models\Domain::firstOrCreate($data, $data)->id;
    }

    private function storeSearchTerms($refererId, $searchTerms)
    {
        foreach (explode(' ', $searchTerms) as $term) {
            $attributes = [
                'referer_id'  => $refererId,
                'search_term' => $term,
            ];
            Models\RefererSearchTerm::firstOrCreate($attributes, $attributes);
        }
    }

    private function getCookieId()
    {
        return 0;
    }

    private function getLanguageId()
    {
        $this->app[LanguageDetector::class];

        return 0;
    }

    /**
     * @return bool
     */
    protected function isRobot()
    {
        /** @var  \Arcanedev\LaravelTracker\Contracts\Detectors\CrawlerDetector  $crawler */
        $crawler = $this->app[CrawlerDetector::class];

        return $crawler->isRobot();
    }

    /**
     * Get the user agent parser.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser
     */
    public function getUserAgentParser()
    {
        return $this->app[UserAgentParser::class];
    }

    private function getCurrentUserAgent()
    {
        return $this->getUserAgentParser()->getOriginalUserAgent();
    }

    private function getCurrentUserAgentData()
    {
        return [
            'name'            => $this->getUserAgentParser()->getOriginalUserAgent() ?: 'Other',
            'browser'         => $this->getUserAgentParser()->getBrowser(),
            'browser_version' => $this->getUserAgentParser()->getUserAgentVersion(),
        ];
    }
}
