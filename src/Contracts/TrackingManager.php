<?php namespace Arcanedev\LaravelTracker\Contracts;

use Illuminate\Routing\Route;
use Illuminate\Http\Request;

/**
 * Interface  TrackingManager
 *
 * @package   Arcanedev\LaravelTracker\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TrackingManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Start the tracking.
     *
     * @param  \Illuminate\Http\Request $request
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
     * Track the exception error.
     *
     * @param  \Exception  $exception
     */
    public function trackException(\Exception $exception);

    /**
     * Enable the tracking.
     */
    public function enable();

    /**
     * Disable the tracking.
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
}
