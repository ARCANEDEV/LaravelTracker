<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     RoutePathParameter
 *
 * @package  Arcanesoft\Tracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int             id
 * @property  int             route_path_id
 * @property  string          parameter
 * @property  string          value
 * @property  \Carbon\Carbon  created_at
 * @property  \Carbon\Carbon  updated_at
 */
class RoutePathParameter extends AbstractModel
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
    protected $table = 'route_path_parameters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['route_path_id', 'parameter', 'value'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'route_path_id' => 'integer',
    ];
}
