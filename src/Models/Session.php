<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     Session
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  string          uuid
 * @property  int             user_id
 * @property  int             device_id
 * @property  int             agent_id
 * @property  string          client_ip
 * @property  int             referrer_id
 * @property  int             cookie_id
 * @property  int             geoip_id
 * @property  int             language_id
 * @property  bool            is_robot
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 *
 * @property  \Arcanedev\LaravelTracker\Models\User      user
 * @property  \Arcanedev\LaravelTracker\Models\Device    device
 * @property  \Arcanedev\LaravelTracker\Models\Agent     agent
 * @property  \Arcanedev\LaravelTracker\Models\Referer   referer
 * @property  \Arcanedev\LaravelTracker\Models\Cookie    cookie
 * @property  \Arcanedev\LaravelTracker\Models\GeoIp     geo_ip
 * @property  \Arcanedev\LaravelTracker\Models\Language  language
 */
class Session extends AbstractModel
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
    protected $table = 'sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'device_id',
        'language_id',
        'agent_id',
        'client_ip',
        'cookie_id',
        'referer_id',
        'geoip_id',
        'is_robot',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id'     => 'integer',
        'device_id'   => 'integer',
        'language_id' => 'integer',
        'agent_id'    => 'integer',
        'cookie_id'   => 'integer',
        'referer_id'  => 'integer',
        'geoip_id'    => 'integer',
        'is_robot'    => 'boolean',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_USER, User::class)
        );
    }

    /**
     * Device relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_DEVICE, Device::class)
        );
    }

    /**
     * Agent relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_AGENT, Agent::class)
        );
    }

    /**
     * Referer relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referer()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_REFERER, Referer::class)
        );
    }

    /**
     * Cookie relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cookie()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_COOKIE, Cookie::class)
        );
    }

    /**
     * GeoIp relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function geoIp()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_GEOIP, GeoIp::class), 'geoip_id'
        );
    }

    /**
     * Language relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_LANGUAGE, Language::class)
        );
    }
}
