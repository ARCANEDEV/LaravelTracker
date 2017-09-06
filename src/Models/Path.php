<?php namespace Arcanedev\LaravelTracker\Models;

use Arcanedev\LaravelTracker\Contracts\Models\Path as PathContract;

/**
 * Class     Path
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  string          path
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class Path extends AbstractModel implements PathContract
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
    protected $table = 'paths';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path'];
}
