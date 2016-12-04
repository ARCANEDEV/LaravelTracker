<?php namespace Arcanedev\LaravelTracker\Contracts\Detectors;

/**
 * Interface  GeoIpDetector
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Detectors
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface GeoIpDetector
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the geoip data.
     *
     * @param  string  $ipAddress
     *
     * @return array|null
     */
    public function search($ipAddress);
}
