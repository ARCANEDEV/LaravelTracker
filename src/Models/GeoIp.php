<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     GeoIp
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  float           latitude
 * @property  float           longitude
 * @property  string          country_code
 * @property  string          country_code3
 * @property  string          country_name
 * @property  string          region
 * @property  string          city
 * @property  string          postal_code
 * @property  int             area_code
 * @property  float           dma_code
 * @property  float           metro_code
 * @property  string          continent_code
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class GeoIp extends Model
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
        'latitude',
        'longitude',
        'country_code',
        'country_code3',
        'country_name',
        'region',
        'city',
        'postal_code',
        'area_code',
        'dma_code',
        'metro_code',
        'continent_code',
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
