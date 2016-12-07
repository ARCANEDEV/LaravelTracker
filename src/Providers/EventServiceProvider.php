<?php namespace Arcanedev\LaravelTracker\Providers;

use Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;

/**
 * Class     EventServiceProvider
 *
 * @package  Arcanedev\LaravelTracker\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class EventServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Illuminate\Routing\Events\RouteMatched::class => [
            \Arcanedev\LaravelTracker\EventListeners\TrackMatchedRoute::class
        ],
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
