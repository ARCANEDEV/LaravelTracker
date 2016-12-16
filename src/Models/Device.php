<?php namespace Arcanedev\LaravelTracker\Models;

use Arcanedev\LaravelTracker\Contracts\Models\Device as DeviceContract;

/**
 * Class     Device
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  string          kind
 * @property  string          model
 * @property  string          platform
 * @property  string          platform_version
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class Device extends AbstractModel implements DeviceContract
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
    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kind',
        'model',
        'platform',
        'platform_version',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Is this a computer?
     *
     * @return bool
     */
    public function isComputer()
    {
        return $this->kind == static::KIND_COMPUTER;
    }

    /**
     * Is this a phone?
     *
     * @return bool
     */
    public function isPhone()
    {
        return $this->kind == static::KIND_PHONE;
    }

    /**
     * Is this a tablet?
     *
     * @return bool
     */
    public function isTablet()
    {
        return $this->kind == static::KIND_TABLET;
    }
}
