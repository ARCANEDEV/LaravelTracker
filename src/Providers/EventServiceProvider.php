<?php namespace Arcanedev\LaravelTracker\Providers;

use Arcanedev\Support\Providers\EventServiceProvider as ServiceProvider;

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
     * Register the application's event listeners.
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
