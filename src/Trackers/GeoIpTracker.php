<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector;
use Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker as GeoIpTrackerContract;
use Arcanedev\LaravelTracker\Models\GeoIp;
use Illuminate\Support\Arr;

/**
 * Class     GeoIpTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class GeoIpTracker implements GeoIpTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector */
    private $geoIpDetector;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * GeoIpTracker constructor.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector  $geoIpDetector
     */
    public function __construct(GeoIpDetector $geoIpDetector)
    {
        $this->geoIpDetector = $geoIpDetector;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the ip address.
     *
     * @param  string  $ipAddress
     *
     * @return int|null
     */
    public function track($ipAddress)
    {
        if ($data = $this->geoIpDetector->search($ipAddress)) {
            $model = GeoIp::firstOrCreate(Arr::only($data, ['latitude', 'longitude']), $data);

            return $model->id;
        }

        return null;
    }
}
