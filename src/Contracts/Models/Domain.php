<?php namespace Arcanedev\LaravelTracker\Contracts\Models;

/**
 * Interface  Domain
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Models
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Illuminate\Database\Eloquent\Collection  referers
 */
interface Domain
{
    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Referer relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referers();
}
