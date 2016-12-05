<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  UserAgentTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface UserAgentTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the user agent.
     *
     * @return int
     */
    public function track();
}
