<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  LanguageTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface LanguageTracker
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the language.
     *
     * @return int
     */
    public function track();
}
