<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     CookieTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CookieTrackerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\CookieTracker */
    private $tracker;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\CookieTracker::class);
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
            \Arcanedev\LaravelTracker\Contracts\Trackers\CookieTracker::class,
            \Arcanedev\LaravelTracker\Trackers\CookieTracker::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        $uuid = '25769c6c-d34d-4bfe-ba98-e0ee856f3e7a';

        static::assertSame(1, $this->tracker->track($uuid));

        static::assertDatabaseHas('tracker_cookies', [
            'id'   => 1,
            'uuid' => $uuid,
        ]);
    }
}
