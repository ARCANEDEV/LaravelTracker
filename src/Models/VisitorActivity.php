<?php namespace Arcanedev\LaravelTracker\Models;

use Arcanedev\LaravelTracker\Contracts\Models\VisitorActivity as VisitorActivityContract;
use Arcanedev\LaravelTracker\Support\BindingManager;

/**
 * Class     VisitorActivity
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  int             visitor_id
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
 * @property  \Arcanedev\LaravelTracker\Models\Visitor  visitor
 * @property  \Arcanedev\LaravelTracker\Models\Path     path
 * @property  \Arcanedev\LaravelTracker\Models\Query    queryRel
 * @property  \Arcanedev\LaravelTracker\Models\Referer  referer
 * @property  \Arcanedev\LaravelTracker\Models\Error    error
 */
class VisitorActivity extends AbstractModel implements VisitorActivityContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visitor_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visitor_id',
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
        'visitor_id'    => 'integer',
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

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Visitor relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visitor()
    {
        return $this->belongsTo(
            $this->getModelClass(BindingManager::MODEL_VISITOR, Visitor::class)
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
            $this->getModelClass(BindingManager::MODEL_PATH, Path::class)
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
            $this->getModelClass(BindingManager::MODEL_QUERY, Query::class)
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
            $this->getModelClass(BindingManager::MODEL_REFERER, Referer::class)
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
            $this->getModelClass(BindingManager::MODEL_ERROR, Error::class)
        );
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the referer exists.
     *
     * @return bool
     */
    public function hasReferer()
    {
        return ! is_null($this->referer);
    }
}
