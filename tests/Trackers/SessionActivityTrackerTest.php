<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     SessionActivityTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SessionActivityTrackerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\SessionActivityTracker */
    private $tracker;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\SessionActivityTracker::class);
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
            \Arcanedev\LaravelTracker\Contracts\Trackers\SessionActivityTracker::class,
            \Arcanedev\LaravelTracker\Trackers\SessionActivityTracker::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        $activity = [
            'session_id'  => 1,
            'method'      => 'GET',
            'path_id'     => null,
            'query_id'    => null,
            'referrer_id' => null,
            'is_ajax'     => false,
            'is_secure'   => false,
            'is_json'     => false,
            'wants_json'  => false,
        ];

        $this->assertSame(1, $this->tracker->track($activity));

        // TODO: add database assertions
    }
}
