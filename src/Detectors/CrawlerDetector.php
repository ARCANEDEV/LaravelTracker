<?php namespace Arcanedev\LaravelTracker\Detectors;

use Arcanedev\LaravelTracker\Contracts\Detectors\CrawlerDetector as CrawlerDetectorContract;

/**
 * Class     CrawlerDetector
 *
 * @package  Arcanedev\LaravelTracker\Contracts\Detectors
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CrawlerDetector implements CrawlerDetectorContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Crawler detector.
     *
     * @var \Jaybizzle\CrawlerDetect\CrawlerDetect
     */
    private $detector;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * CrawlerDetector constructor.
     *
     * @param  \Jaybizzle\CrawlerDetect\CrawlerDetect  $detector
     */
    public function __construct($detector)
    {
        $this->detector = $detector;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if current request is from a bot.
     *
     * @return bool
     */
    public function isRobot()
    {
        return $this->detector->isCrawler();
    }
}
