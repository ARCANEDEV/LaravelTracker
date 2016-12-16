<?php namespace Arcanedev\LaravelTracker\Contracts\Models;

/**
 * Interface  Route
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Models
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Illuminate\Database\Eloquent\Collection  paths
 */
interface Route
{
    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Paths relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paths();
}
