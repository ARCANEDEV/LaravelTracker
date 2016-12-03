<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\LaravelTracker\Contracts\Tracker as TrackerContract;
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
}
