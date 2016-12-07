<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     Route
 *
 * @package  Arcanesoft\Tracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  string          name
 * @property  string          action
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class Route extends AbstractModel
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
    protected $table = 'routes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'action'];

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Paths relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paths()
    {
        return $this->hasMany(
            $this->getConfig('models.route-path', RoutePath::class)
        );
    }
}
