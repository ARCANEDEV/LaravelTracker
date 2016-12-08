<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Detectors\LanguageDetector;
use Arcanedev\LaravelTracker\Contracts\Trackers\LanguageTracker as LanguageTrackerContract;
use Arcanedev\LaravelTracker\Models\AbstractModel;

/**
 * Class     LanguageTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LanguageTracker extends AbstractTracker implements LanguageTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Language
     */
    protected function getModel()
    {
        return $this->makeModel(AbstractModel::MODEL_LANGUAGE);
    }

    /**
     * @return \Arcanedev\LaravelTracker\Contracts\Detectors\LanguageDetector
     */
    private function getLanguageDetector()
    {
        return $this->make(LanguageDetector::class);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the language.
     *
     * @return int
     */
    public function track()
    {
        return $this->getModel()
            ->firstOrCreate(
                $this->getLanguageDetector()->detect()
            )->id;
    }
}
