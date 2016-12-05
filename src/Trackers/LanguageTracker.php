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
class LanguageTracker implements LanguageTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelTracker\Contracts\Detectors\LanguageDetector */
    private $languageDetector;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * LanguageTracker constructor.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\Detectors\LanguageDetector  $languageDetector
     */
    public function __construct(LanguageDetector $languageDetector)
    {
        $this->languageDetector = $languageDetector;
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
        $languages = $this->languageDetector->detect();

        return Language::firstOrCreate($languages)->id;
    }
}
