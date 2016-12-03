<?php namespace Arcanedev\LaravelTracker\Detectors;

use Arcanedev\LaravelTracker\Contracts\Detectors\LanguageDetector as LanguageDetectorContract;

/**
 * Class     LanguageDetector
 *
 * @package  Arcanedev\LaravelTracker\Detectors
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LanguageDetector implements LanguageDetectorContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Jenssegers\Agent\Agent */
    protected $agent;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * LanguageDetector constructor.
     *
     * @param  \Jenssegers\Agent\Agent  $agent
     */
    public function __construct($agent)
    {
        $this->agent = $agent;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Detect preference and language-range.
     *
     * @return array
     */
    public function detect()
    {
        return [
            'preference'     => $this->getLanguagePreference(),
            'language-range' => $this->getLanguageRange(),
        ];
    }

    /**
     * Get language preference.
     *
     * @return string
     */
    public function getLanguagePreference()
    {
        return count($languages = $this->agent->languages())
            ? $languages[0]
            : config('app.locale', 'en');
    }

    /**
     * Get languages ranges.
     *
     * @return string
     */
    public function getLanguageRange()
    {
        return implode(',', $this->agent->languages());
    }
}
