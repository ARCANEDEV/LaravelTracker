<?php namespace Arcanedev\LaravelTracker\Models;

use Arcanedev\LaravelTracker\Contracts\Models\GeoIp as GeoIpContract;

/**
 * Class     GeoIp
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  string          iso_code
 * @property  string          country
 * @property  string          city
 * @property  string          state
 * @property  string          state_code
 * @property  string          postal_code
 * @property  float           latitude
 * @property  float           longitude
 * @property  string          timezone
 * @property  string          continent
 * @property  string          currency
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class GeoIp extends AbstractModel implements GeoIpContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'geoip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'iso_code',
        'country',
        'city',
        'state',
        'state_code',
        'postal_code',
        'latitude',
        'longitude',
        'timezone',
        'continent',
        'currency',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'latitude'      => 'float',
        'longitude'     => 'float',
        'area_code'     => 'int',
        'dma_code'      => 'float',
        'metro_code'    => 'float',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
}
