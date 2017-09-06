<?php namespace Arcanedev\LaravelTracker\Parsers;

use Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser as RefererParserContract;

/**
 * Class     RefererParser
 *
 * @package  Arcanedev\LaravelTracker\Parsers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RefererParser implements RefererParserContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Referer parser instance.
     *
     * @var \Snowplow\RefererParser\Parser
     */
    private $parser;

    /**
     * Parsed referer instance.
     *
     * @var \Snowplow\RefererParser\Referer
     */
    private $referer;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * RefererParser constructor.
     *
     * @param  \Snowplow\RefererParser\Parser  $parser
     */
    public function __construct($parser)
    {
        $this->parser = $parser;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the referer.
     *
     * @param  \Snowplow\RefererParser\Referer  $referer
     *
     * @return self
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get the search medium.
     *
     * @return string|null
     */
    public function getMedium()
    {
        return $this->isKnown() ? $this->referer->getMedium() : null;
    }

    /**
     * Get the search source.
     *
     * @return string|null
     */
    public function getSource()
    {
        return $this->isKnown() ? $this->referer->getSource() : null;
    }

    /**
     * Get the search term.
     *
     * @return string|null
     */
    public function getSearchTerm()
    {
        return $this->isKnown() ? $this->referer->getSearchTerm() : null;
    }

    /**
     * Check if the referer is known.
     *
     * @return bool
     */
    public function isKnown()
    {
        return $this->referer->isKnown();
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Parse a referer.
     *
     * @param  string  $refererUrl
     * @param  string  $pageUrl
     *
     * @return self
     */
    public function parse($refererUrl, $pageUrl)
    {
        $this->setReferer($this->parser->parse($refererUrl, $pageUrl));

        return $this;
    }

    /**
     * Parse the referer url.
     *
     * @param  string  $referer
     *
     * @return array
     */
    public function parseUrl($referer)
    {
        if (is_null($referer)) return null;

        $parsed = parse_url($referer);
        $parts  = explode('.', $parsed['host']);
        $domain = array_pop($parts);

        if (count($parts) > 0)
            $domain = array_pop($parts).'.'.$domain;

        return [
            'url'    => $referer,
            'domain' => $domain,
            'host'   => $parsed['host'],
        ];
    }
}
