<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     QueryArgument
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  int             query_id
 * @property  string          argument
 * @property  string          value
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class QueryArgument extends Model
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
    protected $table = 'query_arguments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['query_id', 'argument', 'value'];

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The query relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function queryRel()
    {
        return $this->belongsTo(
            $this->getConfig('models.query', Query::class)
        );
    }
}
