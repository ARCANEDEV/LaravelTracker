<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     SessionActivity
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  int             session_id
 * @property  int             path_id
 * @property  int             query_id
 * @property  int             referrer_id
 * @property  string          method
 * @property  int             route_path_id
 * @property  bool            is_ajax
 * @property  bool            is_secure
 * @property  bool            is_json
 * @property  bool            wants_json
 * @property  int             error_id
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 *
 * @property  \Arcanedev\LaravelTracker\Models\Session  session
 * @property  \Arcanedev\LaravelTracker\Models\Path     path
 * @property  \Arcanedev\LaravelTracker\Models\Query    queryRel
 * @property  \Arcanedev\LaravelTracker\Models\Referer  referer
 * @property  \Arcanedev\LaravelTracker\Models\Error    error
 */
class SessionActivity extends AbstractModel
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
    protected $table = 'session_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'path_id',
        'query_id',
        'referrer_id',
        'method',
        'route_path_id',
        'is_ajax',
        'is_secure',
        'is_json',
        'wants_json',
        'error_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'session_id'    => 'integer',
        'path_id'       => 'integer',
        'query_id'      => 'integer',
        'referrer_id'   => 'integer',
        'route_path_id' => 'integer',
        'is_ajax'       => 'boolean',
        'is_secure'     => 'boolean',
        'is_json'       => 'boolean',
        'wants_json'    => 'boolean',
        'error_id'      => 'integer',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Session relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_SESSION, Session::class)
        );
    }

    /**
     * Path relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function path()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_PATH, Path::class)
        );
    }

    /**
     * Query relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function queryRel()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_QUERY, Query::class)
        );
    }

    /**
     * Referrer relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_REFERER, Referer::class)
        );
    }

    /**
     * Error relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function error()
    {
        return $this->belongsTo(
            $this->getModelClass(self::MODEL_ERROR, Error::class)
        );
    }
}
