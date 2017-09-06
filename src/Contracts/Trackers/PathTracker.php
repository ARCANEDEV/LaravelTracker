<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  PathTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface PathTracker
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the path.
     *
     * @param  string  $path
     *
     * @return int
     */
    public function track($path);
}
