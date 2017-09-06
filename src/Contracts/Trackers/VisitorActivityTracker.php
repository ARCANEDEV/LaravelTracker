<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  VisitorActivityTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface VisitorActivityTracker
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the visitor activity.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function track(array $data);
}
