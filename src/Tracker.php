<?php namespace Arcanedev\LaravelTracker;

use Arcanedev\LaravelTracker\Contracts\Tracker as TrackerContract;
use Arcanedev\LaravelTracker\Contracts\TrackingManager as TrackingManagerContract;
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
     * The tracking manager.
     *
     * @var \Arcanedev\LaravelTracker\Contracts\TrackingManager
     */
    private $manager;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Tracker constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application         $app
     * @param  \Arcanedev\LaravelTracker\Contracts\TrackingManager  $manager
     */
    public function __construct(Application $app, TrackingManagerContract $manager)
    {
        $this->app     = $app;
        $this->manager = $manager;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Enable the tracking.
     */
    public function enable()
    {
        $this->manager->enable();
    }

    /**
     * Disable the tracking.
     */
    public function disable()
    {
        $this->manager->disable();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the tracking is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->manager->isEnabled();
    }
}
