<?php namespace Arcanedev\LaravelTracker\Tests\Trackers;

use Arcanedev\LaravelTracker\Tests\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelTracker\Tests\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }
}
