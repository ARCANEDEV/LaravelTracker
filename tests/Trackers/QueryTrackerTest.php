<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     QueryTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class QueryTrackerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\QueryTracker */
    protected $tracker;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\QueryTracker::class);
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
    public function it_can_track()
    {
        $queries = [
            'foo' => 'bar',
            'baz' => [
                'world',
                'monde',
                'mondo',
            ],
        ];

        static::assertSame(1, $this->tracker->track($queries));

        // TODO: Add database assertions
    }
}
