<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\SessionActivityTracker as SessionActivityTrackerContract;
use Arcanedev\LaravelTracker\Models\SessionActivity;

/**
 * Class     SessionActivityTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SessionActivityTracker extends AbstractTracker implements SessionActivityTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the session activity.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function track(array $data)
    {
        return SessionActivity::create($data)->id;
    }
}
