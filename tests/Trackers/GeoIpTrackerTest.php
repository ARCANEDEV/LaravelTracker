<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     GeoIpTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class GeoIpTrackerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker */
    private $tracker;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker::class);
    }

    public function tearDown()
    {
        unset($this->tracker);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker::class,
            \Arcanedev\LaravelTracker\Trackers\GeoIpTracker::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        $ip = '128.101.101.101';

        $this->assertSame(1, $this->tracker->track($ip));

        $this->seeInDatabase('tracker_geoip', [
            'id'             => 1,
            'latitude'       => 44.9759,
            'longitude'      => -93.2166,
            'country_code'   => 'US',
            'country_name'   => 'United States',
            'region'         => 'NA',
            'city'           => 'Minneapolis',
            'postal_code'    => '55414',
            'continent_code' => 'NA',
        ]);
    }
}
