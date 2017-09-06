<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser;
use Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker as UserAgentTrackerContract;
use Arcanedev\LaravelTracker\Support\BindingManager;

/**
 * Class     UserAgentTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserAgentTracker extends AbstractTracker implements UserAgentTrackerContract
{
    /* -----------------------------------------------------------------
     |  Getters and Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Agent
     */
    protected function getModel()
    {
        return $this->makeModel(BindingManager::MODEL_AGENT);
    }

    /**
     * Get the user agent parser.
     *
     * @return \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser
     */
    public function getUserAgentParser()
    {
        return $this->make(UserAgentParser::class);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the user agent.
     *
     * @return int
     */
    public function track()
    {
        return $this->getModel()
            ->newQuery()
            ->firstOrCreate($this->prepareData())
            ->getKey();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
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
