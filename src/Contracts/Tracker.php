<?php namespace Arcanedev\LaravelTracker\Contracts;

use Exception;
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
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Start the tracking.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function trackRequest(Request $request);

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
    public function trackException(Exception $exception);

    /**
     * Enable the tracking.
     */
    public function enable();

    /**
     * Disable the tracking.
     */
    public function disable();

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the tracking is enabled.
     *
     * @return bool
     */
    public function isEnabled();
}
