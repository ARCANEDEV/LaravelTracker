<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  RefererTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RefererTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the referer and return the id.
     *
     * @param  string  $refererUrl
     * @param  string  $pageUrl
     *
     * @return int|null
     */
    public function track($refererUrl, $pageUrl);
}
