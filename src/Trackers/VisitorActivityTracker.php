<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\VisitorActivityTracker as VisitorActivityTrackerContract;
use Arcanedev\LaravelTracker\Support\BindingManager;

/**
 * Class     VisitorActivityTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class VisitorActivityTracker extends AbstractTracker implements VisitorActivityTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\VisitorActivity
     */
    protected function getModel()
    {
        return $this->makeModel(BindingManager::MODEL_VISITOR_ACTIVITY);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the visitor activity.
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
