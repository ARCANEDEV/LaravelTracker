<?php namespace Arcanedev\LaravelTracker\Models;

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
class Path extends AbstractModel
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
    protected $table = 'paths';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path'];
}
