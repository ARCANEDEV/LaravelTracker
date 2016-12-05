<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector;
use Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser;
use Arcanedev\LaravelTracker\Models\Device;

/**
 * Class     DeviceTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DeviceTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector */
    protected $detector;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->detector = app(DeviceDetector::class);
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
    private function getUserAgentParser()
    {
        return app(UserAgentParser::class);
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

    /**
     * Get the current device properties.
     *
     * @return array
     */
    public function getCurrentDeviceProperties()
    {
        if ($properties = $this->detector->detect()) {
            $ua = $this->getUserAgentParser();

            $properties['platform']         = $ua->getOperatingSystemFamily();
            $properties['platform_version'] = $ua->getOperatingSystemVersion();
        }

        return $properties;
    }
}
