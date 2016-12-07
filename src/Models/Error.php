<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     Error
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Error extends AbstractModel
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
    protected $table = 'errors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}
