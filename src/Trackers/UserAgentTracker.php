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
class UserAgentTracker extends AbstractTracker implements UserAgentTrackerContract
{
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
        return $this->make(UserAgentParser::class);
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
        $parser = $this->getUserAgentParser();

        return [
            'name'            => $parser->getOriginalUserAgent() ?: 'Other',
            'browser'         => $parser->getBrowser(),
            'browser_version' => $parser->getUserAgentVersion(),
        ];
    }
}
