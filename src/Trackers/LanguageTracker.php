<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Detectors\LanguageDetector;
use Arcanedev\LaravelTracker\Contracts\Trackers\LanguageTracker as LanguageTrackerContract;
use Arcanedev\LaravelTracker\Models\Language;

/**
 * Class     LanguageTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LanguageTracker extends AbstractTracker implements LanguageTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
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
        $languages = $this->getLanguageDetector()->detect();

        return Language::firstOrCreate($languages)->id;
    }
}
