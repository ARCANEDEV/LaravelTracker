<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  SessionTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SessionTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the session.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function track(array $data);

    /**
     * Check the session data.
     *
     * @param  array  $newData
     * @param  array  $currentData
     *
     * @return array
     */
    public function checkData(array $newData, array $currentData);
}
