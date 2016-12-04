<?php namespace Arcanedev\LaravelTracker\Contracts\Detectors;

/**
 * Interface  DeviceDetector
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Detectors
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface DeviceDetector
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Detect kind, model and mobility.
     *
     * @return array
     */
    public function detect();

    /**
     * Get the kind of device.
     *
     * @return string
     */
    public function getDeviceKind();

    /**
     * Is this a tablet?
     *
     * @return bool
     */
    public function isTablet();

    /**
     * Is this a computer?
     *
     * @return bool
     */
    public function isComputer();

    /**
     * Is this a phone?
     *
     * @return bool
     */
    public function isPhone();
}
