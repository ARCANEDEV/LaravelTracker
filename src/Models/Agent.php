<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     Agent
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  string          name
 * @property  string          browser
 * @property  string          browser_version
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class Agent extends AbstractModel
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
    protected $table = 'agents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'browser',
        'browser_version',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
}
