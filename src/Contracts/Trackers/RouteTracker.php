<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

/**
 * Interface  RouteTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RouteTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the route is trackable.
     *
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return bool
     */
    public function isTrackable($route);

    /**
     * Track the matched route.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request   $request
     *
     * @return mixed
     */
    public function track(Route $route, Request $request);
}
