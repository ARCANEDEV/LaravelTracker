<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     UserAgentTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserAgentTrackerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker */
    private $tracker;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker::class);
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
            \Arcanedev\LaravelTracker\Contracts\Trackers\UserAgentTracker::class,
            \Arcanedev\LaravelTracker\Trackers\UserAgentTracker::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        static::assertSame(1, $this->tracker->track());

        // TODO: Add database assertions
    }

    /** @test */
    public function it_can_get_parser()
    {
        $parser = $this->tracker->getUserAgentParser();

        $expectations = [
            \Arcanedev\LaravelTracker\Contracts\Parsers\UserAgentParser::class,
            \Arcanedev\LaravelTracker\Parsers\UserAgentParser::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $parser);
        }
    }
}
