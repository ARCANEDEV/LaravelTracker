<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     VisitorActivityTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class VisitorActivityTrackerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\VisitorActivityTracker */
    private $tracker;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\VisitorActivityTracker::class);
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
            \Arcanedev\LaravelTracker\Contracts\Trackers\VisitorActivityTracker::class,
            \Arcanedev\LaravelTracker\Trackers\VisitorActivityTracker::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        $activity = [
            'visitor_id'  => 1,
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
