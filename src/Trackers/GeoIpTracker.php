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
class GeoIpTracker extends AbstractTracker implements GeoIpTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return \Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector
     */
    private function getGeoIpDetector()
    {
        return $this->make(GeoIpDetector::class);
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
        if ($data = $this->getGeoIpDetector()->search($ipAddress)) {
            $model = GeoIp::firstOrCreate(Arr::only($data, ['latitude', 'longitude']), $data);

            return $model->id;
        }

        return null;
    }
}
