<?php namespace Arcanedev\LaravelTracker\Contracts;

use Illuminate\Http\Request;

/**
 * Interface  Tracker
 *
 * @package   Arcanedev\LaravelTracker\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Tracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Start the tracking.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function track(Request $request);

    /**
     * Enable the tracker.
     */
    public function enable();

    /**
     * Disable the tracker.
     */
    public function disable();

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the tracker is enabled.
     *
     * @return bool
     */
    public function isEnabled();

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
}
