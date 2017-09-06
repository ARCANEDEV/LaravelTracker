<?php namespace Arcanedev\LaravelTracker\Parsers;

use Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser as UserAgentParserContract;

/**
 * Class     UserAgentParser
 *
 * @package  Arcanedev\LaravelTracker\Parsers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserAgentParser implements UserAgentParserContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \UAParser\Parser */
    protected $parser;

    /** @var string */
    public $basePath;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * UserAgentParser constructor.
     *
     * @param  \UAParser\Parser  $parser
     * @param  string            $basePath
     * @param  null              $userAgent
     */
    public function __construct($parser, $basePath, $userAgent = null)
    {
        if ( ! $userAgent && isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $this->parser   = $parser->parse($userAgent);
        $this->basePath = $basePath;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the OS version.
     *
     * @return string
     */
    public function getOperatingSystemVersion()
    {
        $os = $this->getOperatingSystem();

        return $this->prepareVersion([$os->major, $os->minor, $os->patch]);
    }

    /**
     * Get the OS Family.
     *
     * @return string|null
     */
    public function getOperatingSystemFamily()
    {
        try {
            return $this->parser->os->family;
        }
        catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the browser.
     *
     * @return string
     */
    public function getBrowser()
    {
        return $this->getUserAgent()->family;
    }

    /**
     * Get the user agent version.
     *
     * @return string
     */
    public function getUserAgentVersion()
    {
        $ua = $this->getUserAgent();

        return $this->prepareVersion([$ua->major, $ua->minor, $ua->patch]);
    }

    /**
     * Get the original user agent.
     *
     * @return string
     */
    public function getOriginalUserAgent()
    {
        return $this->parser->originalUserAgent;
    }

    /**
     * Get the user agent.
     *
     * @return \UAParser\Result\UserAgent
     */
    public function getUserAgent()
    {
        return $this->parser->ua;
    }

    /**
     * Get the operating system.
     *
     * @return \UAParser\Result\OperatingSystem
     */
    public function getOperatingSystem()
    {
        return $this->parser->os;
    }

    /**
     * Get the device.
     *
     * @return \UAParser\Result\Device
     */
    public function getDevice()
    {
        return $this->parser->device;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Prepare the version.
     *
     * @param  array  $version
     *
     * @return string
     */
    protected function prepareVersion(array $version)
    {
        return implode('.', array_filter($version));
    }
}
