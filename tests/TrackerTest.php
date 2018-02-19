<?php namespace Arcanedev\LaravelTracker\Tests;

/**
 * Class     TrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrackerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelTracker\Tracker  */
    private $tracker;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->migrate();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Tracker::class);
        $this->tracker->enable();
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
            \Arcanedev\LaravelTracker\Contracts\Tracker::class,
            \Arcanedev\LaravelTracker\Tracker::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->tracker);
        }
        static::assertTrue($this->tracker->isEnabled());
    }

    /** @test */
    public function it_can_be_instantiated_via_contract()
    {
        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Tracker::class);

        $expectations = [
            \Arcanedev\LaravelTracker\Contracts\Tracker::class,
            \Arcanedev\LaravelTracker\Tracker::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->tracker);
        }
        static::assertTrue($this->tracker->isEnabled());
    }

    /** @test */
    public function it_can_enable_and_disable()
    {
        static::assertTrue($this->tracker->isEnabled());

        $this->tracker->disable();

        static::assertFalse($this->tracker->isEnabled());

        $this->tracker->enable();

        static::assertTrue($this->tracker->isEnabled());
    }

    /** @test */
    public function it_can_track()
    {
        // Without an authenticated user
        $this->call('GET', '/');

        $activities = \Arcanedev\LaravelTracker\Models\VisitorActivity::all();

        static::assertCount(1, $activities);

        static::assertNull($activities->first()->error_id);

        // TODO: Adding more test assertions ?
    }
}
