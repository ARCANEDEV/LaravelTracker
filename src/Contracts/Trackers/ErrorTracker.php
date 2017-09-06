<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

use Exception;

/**
 * Interface  ErrorTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ErrorTracker
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the exception error.
     *
     * @param  \Exception  $exception
     *
     * @return int
     */
    public function track(Exception $exception);
}
