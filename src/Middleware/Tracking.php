<?php namespace Arcanedev\LaravelTracker\Middleware;

use Arcanedev\LaravelTracker\Contracts\Tracker;
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
     * @var  \Arcanedev\LaravelTracker\Contracts\Tracker
     */
    protected $tracker;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Tracking constructor.
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
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->tracker->trackRequest($request);

        return $next($request);
    }
}
