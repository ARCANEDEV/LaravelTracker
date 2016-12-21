<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  VisitorTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface VisitorTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the visitor activity.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function track(array $data);

    /**
     * Check the visitor data.
     *
     * @param  array  $currentData
     * @param  array  $newData
     *
     * @return array
     */
    public function checkData(array $currentData, array $newData);
}
