<?php namespace Arcanedev\LaravelTracker\Contracts\Parsers;

/**
 * Interface  RefererParser
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Parsers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface RefererParser
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the referer.
     *
     * @param  \Snowplow\RefererParser\Referer  $referer
     *
     * @return self
     */
    public function setReferer($referer);

    /**
     * Get the search medium.
     *
     * @return string|null
     */
    public function getMedium();

    /**
     * Get the search source.
     *
     * @return string|null
     */
    public function getSource();

    /**
     * Get the search term.
     *
     * @return string|null
     */
    public function getSearchTerm();

    /**
     * Check if the referer is known.
     *
     * @return bool
     */
    public function isKnown();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Parse a referer.
     *
     * @param  string  $refererUrl
     * @param  string  $pageUrl
     *
     * @return self
     */
    public function parse($refererUrl, $pageUrl);

    /**
     * Parse the referrer url.
     *
     * @param  string  $referer
     *
     * @return array
     */
    public function parseUrl($referer);
}
