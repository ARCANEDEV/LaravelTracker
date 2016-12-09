<?php namespace Arcanedev\LaravelTracker\Models\Presenters;

/**
 * Class     SessionPresenter
 *
 * @package  Arcanedev\LaravelTracker\Models\Presenters
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  location_name
 *
 * @property  \Arcanedev\LaravelTracker\Models\GeoIp  geo_ip
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
        return is_null($this->geo_ip)
            ? 'undefined'
            : $this->geo_ip->country_name . ' ' . $this->geo_ip->city;
    }
}
