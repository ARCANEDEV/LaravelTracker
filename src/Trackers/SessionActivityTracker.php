<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\SessionActivityTracker as SessionActivityTrackerContract;
use Arcanedev\LaravelTracker\Support\BindingManager;

/**
 * Class     SessionActivityTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SessionActivityTracker extends AbstractTracker implements SessionActivityTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\SessionActivity
     */
    protected function getModel()
    {
        return $this->makeModel(BindingManager::MODEL_SESSION_ACTIVITY);
    }

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
        $model = $this->getModel()->fill($data);
        $model->save();

        return $model->id;
    }
}
