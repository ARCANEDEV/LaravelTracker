<?php namespace Arcanedev\LaravelTracker\Models\Presenters;

/**
 * Class     SessionPresenter
 *
 * @package  Arcanedev\LaravelTracker\Models\Presenters
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  location_name
 *
 * @property  \Arcanedev\LaravelTracker\Models\GeoIp  geoip
 */
trait SessionPresenter
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the `location_name` attribute.
     *
     * @return string
     */
    public function getLocationNameAttribute()
    {
        return $this->hasGeoip() ? $this->geoip->country.' '.$this->geoip->city : 'undefined';
    }
}
