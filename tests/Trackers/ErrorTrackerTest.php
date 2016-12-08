<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     ErrorTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ErrorTrackerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\ErrorTracker */
    private $tracker;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\ErrorTracker::class);
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
            \Arcanedev\LaravelTracker\Contracts\Trackers\ErrorTracker::class,
            \Arcanedev\LaravelTracker\Trackers\ErrorTracker::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_track()
    {
        $exception = new \Exception('Page not found exception.', 404);

        $this->assertSame(1, $this->tracker->track($exception));

        $this->seeInDatabase('tracker_errors', [
            'id'      => 1,
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
        ]);
    }

    /** @test */
    public function it_can_track_an_exception_with_handler()
    {
        try {
            $this->call('GET', 'page-not-found');
        }
        catch (\Exception $e) {

        }

        // TODO: Complete the test assertions
    }
}
