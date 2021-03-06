<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     GeoIpTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class GeoIpTrackerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker */
    private $tracker;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelTracker\Contracts\Trackers\GeoIpTracker::class,
            \Arcanedev\LaravelTracker\Trackers\GeoIpTracker::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        $ip = '128.101.101.101';

        static::assertSame(1, $this->tracker->track($ip));

        $this->assertDatabaseHas('tracker_geoip', [
            'iso_code'    => 'US',
            'country'     => 'United States',
            'city'        => 'Minneapolis',
            'state'       => 'Minnesota',
            'state_code'  => 'MN',
            'postal_code' => '55414',
            'latitude'    => 44.9759,
            'longitude'   => -93.2166,
            'timezone'    => 'America/Chicago',
            'continent'   => 'NA',
            'currency'    => 'USD',
        ]);
    }
}
