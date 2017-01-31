<?php namespace Arcanedev\LaravelTracker\Facades;

use Arcanedev\LaravelTracker\Contracts\Tracker as TrackerContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class     Tracker
 *
 * @package  Arcanedev\LaravelTracker\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Tracker extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return TrackerContract::class; }
}
