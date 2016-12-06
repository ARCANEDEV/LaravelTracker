<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     SessionTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SessionTrackerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\SessionTracker */
    private $tracker;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\SessionTracker::class);
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
            \Arcanedev\LaravelTracker\Contracts\Trackers\SessionTracker::class,
            \Arcanedev\LaravelTracker\Trackers\SessionTracker::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        $data = $this->getSessionData();

        $this->assertSame(1, $this->tracker->track($data));

        // TODO: Add database assertions
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the session data.
     *
     * @return array
     */
    private function getSessionData()
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
            // if the user agent changed during a session.
            'user_agent'   => '',
        ];
    }
}
