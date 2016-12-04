<?php namespace Arcanedev\LaravelTracker\Contracts\Parsers;

/**
 * Interface  UserAgentParser
 *
 * @package   Arcanedev\LaravelTracker\Contracts\Parsers
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface UserAgentParser
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the OS version.
     *
     * @return string
     */
    public function getOperatingSystemVersion();

    /**
     * Get the OS Family.
     *
     * @return string|null
     */
    public function getOperatingSystemFamily();

    /**
     * Get the browser.
     *
     * @return string
     */
    public function getBrowser();

    /**
     * Get the user agent version.
     *
     * @return string
     */
    public function getUserAgentVersion();

    /**
     * Get the original user agent.
     *
     * @return string
     */
    public function getOriginalUserAgent();

    /**
     * Get the user agent.
     *
     * @return \UAParser\Result\UserAgent
     */
    public function getUserAgent();

    /**
     * Get the operating system.
     *
     * @return \UAParser\Result\OperatingSystem
     */
    public function getOperatingSystem();

    /**
     * Get the device.
     *
     * @return \UAParser\Result\Device
     */
    public function getDevice();
}
