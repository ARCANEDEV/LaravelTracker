<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  CookieTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CookieTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the cookie.
     *
     * @param  mixed  $cookie
     *
     * @return int
     */
    public function track($cookie);
}
