<?php namespace Arcanedev\LaravelTracker\Contracts\Models;

/**
 * Interface  Visitor
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Models
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Arcanedev\LaravelTracker\Models\User      user
 * @property  \Arcanedev\LaravelTracker\Models\Device    device
 * @property  \Arcanedev\LaravelTracker\Models\Agent     agent
 * @property  \Arcanedev\LaravelTracker\Models\Referer   referer
 * @property  \Arcanedev\LaravelTracker\Models\Cookie    cookie
 * @property  \Arcanedev\LaravelTracker\Models\GeoIp     geoip
 * @property  \Arcanedev\LaravelTracker\Models\Language  language
 */
interface Visitor
{
    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Device relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device();

    /**
     * Agent relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent();

    /**
     * Referer relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referer();

    /**
     * Cookie relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cookie();

    /**
     * GeoIp relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function geoip();

    /**
     * Language relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language();

    /**
     * Visitor activities relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities();

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the user exists.
     *
     * @return bool
     */
    public function hasUser();

    /**
     * Check if the geoip exists.
     *
     * @return bool
     */
    public function hasGeoip();

    /**
     * Check if the user agent exists.
     *
     * @return bool
     */
    public function hasUserAgent();

    /**
     * Check if the device exists.
     *
     * @return bool
     */
    public function hasDevice();

    /**
     * Check if the referer exists.
     *
     * @return bool
     */
    public function hasReferer();

    /**
     * Check if the cookie exists.
     *
     * @return bool
     */
    public function hasCookie();
}
