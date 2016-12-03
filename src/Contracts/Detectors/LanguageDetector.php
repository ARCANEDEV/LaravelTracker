<?php namespace Arcanedev\LaravelTracker\Contracts\Detectors;

/**
 * Interface  LanguageDetector
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Detectors
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface LanguageDetector
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Detect preference and language-range.
     *
     * @return array
     */
    public function detect();

    /**
     * Get language preference.
     *
     * @return string
     */
    public function getLanguagePreference();

    /**
     * Get languages ranges.
     *
     * @return string
     */
    public function getLanguageRange();
}
