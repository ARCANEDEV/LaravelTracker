<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     VisitorTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class VisitorTrackerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\VisitorTracker */
    private $tracker;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\VisitorTracker::class);
    }

    public function tearDown()
    {
        unset($this->tracker);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Test Methods
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelTracker\Contracts\Trackers\VisitorTracker::class,
            \Arcanedev\LaravelTracker\Trackers\VisitorTracker::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        $data = $this->getVisitorData();

        static::assertSame(1, $this->tracker->track($data));

        // TODO: Add database assertions
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the visitor data.
     *
     * @return array
     */
    private function getVisitorData()
    {
        return [
            'user_id'      => 1,
            'device_id'    => 1,
            'client_ip'    => '127.0.0.1',
            'geoip_id'     => 1,
            'agent_id'     => 1,
            'referrer_id'  => 1,
            'cookie_id'    => 1,
            'language_id'  => 1,
            'is_robot'     => true,

            // The key user_agent is not present in the sessions table, but it's internally used to check
            // if the user agent changed during a visitor.
            'user_agent'   => '',
        ];
    }
}
