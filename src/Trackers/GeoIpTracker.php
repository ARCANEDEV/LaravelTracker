<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector;
use Arcanedev\LaravelTracker\Models\GeoIp;
use Illuminate\Support\Arr;

/**
 * Class     GeoIpTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class GeoIpTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelTracker\Contracts\Detectors\GeoIpDetector */
    protected $detector;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * GeoIpTracker constructor.
     */
    public function __construct()
    {
        $this->detector = app(GeoIpDetector::class);
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
        $data = $this->detector->search($ipAddress);

        if ($data) {
            $model = GeoIp::firstOrCreate(Arr::only($data, ['latitude', 'longitude']), $data);

            return $model->id;
        }

        return null;
    }
}
