<?php namespace Arcanedev\LaravelTracker\Contracts\Models;

/**
 * Interface  RoutePath
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Models
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Arcanedev\LaravelTracker\Models\Route    route
 * @property  \Illuminate\Database\Eloquent\Collection  parameters
 */
interface RoutePath
{
    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Route relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route();

    /**
     * Parameters relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parameters();
}
