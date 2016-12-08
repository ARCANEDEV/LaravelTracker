<?php namespace Arcanedev\LaravelTracker\EventListeners;

use Arcanedev\LaravelTracker\Contracts\TrackingManager;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Route;

/**
 * Class     RouteEventSubscriber
 *
 * @package  Arcanedev\LaravelTracker\EventListeners
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteEventSubscriber
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelTracker\Contracts\TrackingManager */
    private $manager;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * RouteEventSubscriber constructor.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\TrackingManager  $manager
     */
    public function __construct(TrackingManager $manager)
    {
        $this->manager = $manager;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = self::class;

        $events->listen('Illuminate\Routing\Events\RouteMatched', "$class@handle");
        $events->listen('router.matched',                         "$class@handleOldEvent");
    }

    /**
     * Track the matched route.
     *
     * @param  \Illuminate\Routing\Events\RouteMatched  $event
     */
    public function handle(RouteMatched $event)
    {
        $this->manager->trackMatchedRoute($event->route, $event->request);
    }

    /**
     * Track the matched route (old event on Laravel 5.1).
     *
     * @param  \Illuminate\Routing\Route  $route
     */
    public function handleOldEvent(Route $route)
    {
        $this->manager->trackMatchedRoute($route, request());
    }
}
