<?php namespace Arcanedev\LaravelTracker\Contracts\Detectors;

/**
 * Interface  CrawlerDetector
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Detectors
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CrawlerDetector
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if current request is from a bot.
     *
     * @return bool
     */
    public function isRobot();
}
