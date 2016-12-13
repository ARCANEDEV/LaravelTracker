<?php namespace Arcanedev\LaravelTracker\Detectors;

use Arcanedev\LaravelTracker\Contracts\Detectors\DeviceDetector as DeviceDetectorContract;
use Arcanedev\LaravelTracker\Models\Device;

/**
 * Class     DeviceDetector
 *
 * @package  Arcanedev\LaravelTracker\Detectors
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DeviceDetector implements DeviceDetectorContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Jenssegers\Agent\Agent */
    protected $agent;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * DeviceDetector constructor.
     *
     * @param  \Jenssegers\Agent\Agent  $agent
     */
    public function __construct($agent)
    {
        $this->agent = $agent;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Detect kind, model and mobility.
     *
     * @return array
     */
    public function detect()
    {
        return [
            'kind'      => $this->getDeviceKind(),
            'model'     => $this->agent->device() ?: '',
        ];
    }

    /**
     * Get the kind of device.
     *
     * @return string
     */
    public function getDeviceKind()
    {
        if ($this->isTablet())   return Device::KIND_TABLET;
        if ($this->isPhone())    return Device::KIND_PHONE;
        if ($this->isComputer()) return Device::KIND_COMPUTER;

        return Device::KIND_UNAVAILABLE;
    }

    /**
     * Is this a tablet?
     *
     * @return bool
     */
    public function isTablet()
    {
        return $this->agent->isTablet();
    }

    /**
     * Is this a computer?
     *
     * @return bool
     */
    public function isComputer()
    {
        return ! $this->agent->isMobile();
    }

    /**
     * Is this a phone?
     *
     * @return bool
     */
    public function isPhone()
    {
        return ! ($this->isTablet() || $this->isComputer());
    }
}
