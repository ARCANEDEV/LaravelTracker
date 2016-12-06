<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  SessionActivityTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SessionActivityTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the session activity.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function track(array $data);
}
