<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector;
use Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser;
use Arcanedev\LaravelTracker\Contracts\Trackers\DeviceTracker as DeviceTrackerContract;
use Arcanedev\LaravelTracker\Models\Device;

/**
 * Class     DeviceTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DeviceTracker implements DeviceTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector */
    private $deviceDetector;

    /** @var \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser */
    private $userAgentParser;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * DeviceTracker constructor.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector  $deviceDetector
     * @param  \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser   $userAgentParser
     */
    public function __construct(DeviceDetector $deviceDetector, UserAgentParser $userAgentParser)
    {
        $this->deviceDetector  = $deviceDetector;
        $this->userAgentParser = $userAgentParser;
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
        $data  = $this->getCurrentDeviceProperties();
        $model = Device::firstOrCreate($data, $data);

        return $model->id;
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
        if ($properties = $this->deviceDetector->detect()) {
            $properties['platform']         = $this->userAgentParser->getOperatingSystemFamily();
            $properties['platform_version'] = $this->userAgentParser->getOperatingSystemVersion();
        }

        return $properties;
    }
}
