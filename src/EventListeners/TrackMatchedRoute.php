<?php namespace Arcanedev\LaravelTracker\EventListeners;

use Arcanedev\LaravelTracker\Contracts\Tracker;
use Illuminate\Routing\Events\RouteMatched;

/**
 * Class     TrackMatchedRoute
 *
 * @package  Arcanedev\LaravelTracker\EventListeners
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrackMatchedRoute
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelTracker\Contracts\Tracker */
    private $tracker;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */

    /**
     * Create the event listener.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\Tracker  $tracker
     */
    public function __construct(Tracker $tracker)
    {
        $this->tracker = $tracker;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Routing\Events\RouteMatched  $event
     */
    public function handle(RouteMatched $event)
    {
        $this->tracker->trackMatchedRoute($event->route, $event->request);
    }
}
