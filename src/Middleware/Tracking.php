<?php namespace Arcanedev\LaravelTracker\Middleware;

use Arcanedev\LaravelTracker\Contracts\TrackingManager as TrackingManagerContract;
use Closure;

/**
 * Class     Tracking
 *
 * @package  Arcanedev\LaravelTracker\Middleware
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Tracking
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The tracker instance.
     *
     * @var  \Arcanedev\LaravelTracker\Contracts\TrackingManager
     */
    protected $manager;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Tracking constructor.
     */
    public function __construct(TrackingManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->manager->trackRequest($request);

        return $next($request);
    }
}
