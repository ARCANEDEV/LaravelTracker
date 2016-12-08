<?php namespace Arcanedev\LaravelTracker\Contracts;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

/**
 * Interface  Tracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Tracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Start the tracking.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function track(Request $request);

    /**
     * Track the matched route.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request   $request
     */
    public function trackMatchedRoute(Route $route, Request $request);

    /**
     * Track the exception.
     *
     * @param  \Exception  $exception
     */
    public function trackException(\Exception $exception);

    /**
     * Enable the tracker.
     */
    public function enable();

    /**
     * Disable the tracker.
     */
    public function disable();

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the tracker is enabled.
     *
     * @return bool
     */
    public function isEnabled();

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
}
