<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  QueryTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface QueryTracker
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the query.
     *
     * @param  array  $queries
     *
     * @return int|null
     */
    public function track(array $queries);
}
