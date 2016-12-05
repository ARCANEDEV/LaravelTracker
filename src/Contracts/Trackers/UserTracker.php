<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  UserTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface UserTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the current authenticated user id.
     *
     * @return int|null
     */
    public function track();
}
