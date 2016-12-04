<?php namespace Arcanedev\LaravelTracker\Contracts\Detectors;

/**
 * Interface  UserDetector
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Detectors
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface UserDetector
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the user is authenticated.
     *
     * @return bool
     */
    public function check();

    /**
     * Get the authenticated user.
     *
     * @return false|mixed
     */
    public function user();

    /**
     * Get the current authenticated user id.
     *
     * @return int|null
     */
    public function getCurrentUserId();
}
