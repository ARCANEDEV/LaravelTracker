<?php namespace Arcanedev\LaravelTracker\Contracts\Trackers;

/**
 * Interface  UserAgentTracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Trackers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface UserAgentTracker
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the user agent parser.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser
     */
    public function getUserAgentParser();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the user agent.
     *
     * @return int
     */
    public function track();
}
