<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser;
use Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker as UserAgentTrackerContract;
use Arcanedev\LaravelTracker\Models\Agent;

/**
 * Class     UserAgentTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserAgentTracker implements UserAgentTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser */
    protected $userAgentParser;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * UserAgentTracker constructor.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser  $userAgentParser
     */
    public function __construct(UserAgentParser $userAgentParser)
    {
        $this->userAgentParser = $userAgentParser;
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
    public function getUserAgentParser()
    {
        return $this->userAgentParser;
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
        return Agent::firstOrCreate($data = $this->prepareData(), $data)->id;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare the data.
     *
     * @return array
     */
    private function prepareData()
    {
        return [
            'name'            => $this->userAgentParser->getOriginalUserAgent() ?: 'Other',
            'browser'         => $this->userAgentParser->getBrowser(),
            'browser_version' => $this->userAgentParser->getUserAgentVersion(),
        ];
    }
}
