<?php namespace Arcanedev\LaravelTracker\Contracts\Models;

/**
 * Interface  Referer
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Models
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Arcanedev\LaravelTracker\Models\Domain   domain
 * @property  \Illuminate\Database\Eloquent\Collection  search_terms
 */
interface Referer
{
    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Domain relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain();

    /**
     * Search terms relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function searchTerms();
}
