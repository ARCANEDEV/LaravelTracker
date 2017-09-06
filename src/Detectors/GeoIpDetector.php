<?php namespace Arcanedev\LaravelTracker\Detectors;

use Arcanedev\GeoIP\Contracts\GeoIP;
use Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector as GeoIpDetectorContract;

/**
 * Class     GeoIpDetector
 *
 * @package  Arcanedev\LaravelTracker\Detectors
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class GeoIpDetector implements GeoIpDetectorContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * @var \Arcanedev\GeoIP\Contracts\GeoIP
     */
    private $geoIp;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * GeoIpDetector constructor.
     *
     * @param  \Arcanedev\GeoIP\Contracts\GeoIP  $geoIp
     */
    public function __construct(GeoIP $geoIp)
    {
        $this->geoIp = $geoIp;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the geoip data.
     *
     * @param  string  $ipAddress
     *
     * @return array|null
     */
    public function search($ipAddress)
    {
        try {
            if ($location = $this->geoIp->location($ipAddress)) {
                return $this->transform($location);
            }
        }
        catch (\Exception $e) {
            // do nothing
        }

        return null;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Render the data.
     *
     * @param  \Arcanedev\GeoIP\Location  $location
     *
     * @return array
     */
    private function transform($location)
    {
        return [
            'iso_code'    => $location->iso_code,
            'country'     => $location->country,
            'city'        => $location->city,
            'state'       => $location->state,
            'state_code'  => $location->state_code,
            'postal_code' => $location->postal_code,
            'latitude'    => $location->latitude,
            'longitude'   => $location->longitude,
            'timezone'    => $location->timezone,
            'continent'   => $location->continent,
            'currency'    => $location->currency,
        ];
    }
}
