<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\PathTracker as PathTrackerContract;
use Arcanedev\LaravelTracker\Models\AbstractModel;

/**
 * Class     PathTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PathTracker extends AbstractTracker implements PathTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Path
     */
    protected function getModel()
    {
        return $this->makeModel(AbstractModel::MODEL_PATH);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the path.
     *
     * @param  string  $path
     *
     * @return int
     */
    public function track($path)
    {
        return $this->getModel()
                    ->firstOrCreate(compact('path'))->id;
    }
}
