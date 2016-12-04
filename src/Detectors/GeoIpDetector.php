<?php namespace Arcanedev\LaravelTracker\Detectors;

use Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector as GeoIpDetectorContract;
use GeoIp2\Database\Reader as GeoIpReader;
use GeoIp2\Exception\AddressNotFoundException;

/**
 * Class     GeoIpDetector
 *
 * @package  Arcanedev\LaravelTracker\Detectors
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class GeoIpDetector implements GeoIpDetectorContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private $reader;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->reader = new GeoIpReader($this->getGeoliteFileName());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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
            if ($cityModel = $this->reader->city($ipAddress)) {
                return $this->renderData($cityModel);
            }
        }
        catch (AddressNotFoundException $e) {
            // do nothing
        }

        return null;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Render the data.
     *
     * @param  \GeoIp2\Model\City  $cityModel
     *
     * @return array
     */
    private function renderData($cityModel)
    {
        return [
            'latitude'       => $cityModel->location->latitude,
            'longitude'      => $cityModel->location->longitude,
            'country_code'   => $cityModel->country->isoCode,
            'country_code3'  => null,
            'country_name'   => $cityModel->country->name,
            'region'         => $cityModel->continent->code,
            'city'           => $cityModel->city->name,
            'postal_code'    => $cityModel->postal->code,
            'area_code'      => null,
            'dma_code'       => null,
            'metro_code'     => $cityModel->location->metroCode,
            'continent_code' => $cityModel->continent->code,
        ];
    }

    /**
     * Get the GeoLiteFileName.
     *
     * @return string
     */
    private function getGeoliteFileName()
    {
        return __DIR__ . '/../../data/GeoLite2-City.mmdb';
    }
}
