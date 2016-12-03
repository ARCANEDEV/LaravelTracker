<?php namespace Arcanedev\LaravelTracker\Middleware;

use Arcanedev\LaravelTracker\Contracts\Tracker as TrackerContract;
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
     */
    public function __construct(TrackerContract $tracker)
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
        $this->tracker->track($request);

        return $next($request);
    }
}
