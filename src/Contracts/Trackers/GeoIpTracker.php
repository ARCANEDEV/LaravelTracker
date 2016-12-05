<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  GeoIpTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface GeoIpTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the ip address.
     *
     * @param  string  $ipAddress
     *
     * @return int|null
     */
    public function track($ipAddress);
}
