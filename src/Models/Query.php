<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     Query
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  string          query
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class Query extends Model
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
    protected $table = 'queries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['query'];

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The arguments relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function arguments()
    {
        return $this->hasMany(
            $this->getConfig('models.query-argument', QueryArgument::class)
        );
    }
}
