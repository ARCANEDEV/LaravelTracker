<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\LaravelTracker\Contracts\Tracker as TrackerContract;
use Illuminate\Contracts\Foundation\Application;

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
    protected function config()
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
    protected function getConfig($key, $default = null)
    {
        return $this->config()->get("laravel-tracker.$key", $default);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
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
