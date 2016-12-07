<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

/**
 * Class     RouteTrackerTest
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteTrackerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelTracker\Contracts\Trackers\RouteTracker */
    private $tracker;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->tracker = $this->app->make(\Arcanedev\LaravelTracker\Contracts\Trackers\RouteTracker::class);
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
            \Arcanedev\LaravelTracker\Contracts\Trackers\RouteTracker::class,
            \Arcanedev\LaravelTracker\Trackers\RouteTracker::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->tracker);
        }
    }

    /** @test */
    public function it_can_check_if_trackable()
    {
        $route = $this->makeContactRoute();

        $this->assertTrue($this->tracker->isTrackable($route));

        $route = $this->makeAdminRoute();

        $this->assertFalse($this->tracker->isTrackable($route));
    }

    /** @test */
    public function it_can_track()
    {
        // TODO: Add database assertions
    }

    /**
     * Make contact route.
     *
     * @return \Illuminate\Routing\Route
     */
    private function makeContactRoute()
    {
        return $this->makeRoute('GET', 'contact', [
            'as'   => 'public::contact.get',
            'uses' => 'ContactController@getForm',
        ]);
    }

    /**
     * Make admin route.
     *
     * @return \Illuminate\Routing\Route
     */
    private function makeAdminRoute()
    {
        return $this->makeRoute('GET', 'admin', [
            'as'   => 'admin::home',
            'uses' => 'AdminController@index',
        ]);
    }
}
