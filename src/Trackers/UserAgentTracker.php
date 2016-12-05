<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser;
use Arcanedev\LaravelTracker\Models\Agent;

/**
 * Class     UserAgentTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserAgentTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser */
    protected $parser;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * UserAgentTracker constructor.
     */
    public function __construct()
    {
        $this->parser = app(UserAgentParser::class);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the user agent parser.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the user agent.
     *
     * @return int
     */
    public function track()
    {
        $data = [
            'name'            => $this->parser->getOriginalUserAgent() ?: 'Other',
            'browser'         => $this->parser->getBrowser(),
            'browser_version' => $this->parser->getUserAgentVersion(),
        ];

        $model = Agent::firstOrCreate($data, $data);

        return $model->id;
    }
}
