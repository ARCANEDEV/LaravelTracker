<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector;
use Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser;
use Arcanedev\LaravelTracker\Contracts\Trackers\DeviceTracker as DeviceTrackerContract;
use Arcanedev\LaravelTracker\Models\AbstractModel;

/**
 * Class     DeviceTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DeviceTracker extends AbstractTracker implements DeviceTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Device
     */
    protected function getModel()
    {
        return $this->makeModel(AbstractModel::MODEL_DEVICE);
    }

    /**
     * @return \Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector
     */
    private function getDeviceDetector()
    {
        return $this->make(DeviceDetector::class);
    }

    /**
     * @return \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser
     */
    private function getUserAgentParser()
    {
        return $this->make(UserAgentParser::class);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the device.
     *
     * @return int
     */
    public function track()
    {
        return $this->getModel()
                    ->firstOrCreate($this->getCurrentDeviceProperties())->id;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the current device properties.
     *
     * @return array
     */
    private function getCurrentDeviceProperties()
    {
        if ($properties = $this->getDeviceDetector()->detect()) {
            $parser = $this->getUserAgentParser();

            $properties['platform']         = $parser->getOperatingSystemFamily();
            $properties['platform_version'] = $parser->getOperatingSystemVersion();
        }

        return $properties;
    }
}
