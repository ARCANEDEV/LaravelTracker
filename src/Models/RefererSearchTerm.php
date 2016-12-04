<?php namespace Arcanedev\LaravelTracker\Models;

/**
 * Class     RefererSearchTerm
 *
 * @package  Arcanesoft\Tracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RefererSearchTerm extends Model
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
    protected $table = 'referers_search_terms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['referer_id', 'search_term'];
}
