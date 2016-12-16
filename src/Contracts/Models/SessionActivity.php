<?php namespace Arcanedev\LaravelTracker\Contracts\Models;

/**
 * Interface  SessionActivity
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Models
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Arcanedev\LaravelTracker\Models\Session  session
 * @property  \Arcanedev\LaravelTracker\Models\Path     path
 * @property  \Arcanedev\LaravelTracker\Models\Query    queryRel
 * @property  \Arcanedev\LaravelTracker\Models\Referer  referer
 * @property  \Arcanedev\LaravelTracker\Models\Error    error
 */
interface SessionActivity
{
    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Session relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session();

    /**
     * Path relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function path();

    /**
     * Query relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function queryRel();

    /**
     * Referrer relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer();

    /**
     * Error relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function error();

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the referer exists.
     *
     * @return bool
     */
    public function hasReferer();
}
