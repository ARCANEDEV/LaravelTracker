<?php namespace Arcanedev\LaravelTracker\Bases;

use Arcanedev\Support\Bases\Migration as BaseMigration;

/**
 * Class     Migration
 *
 * @package  Arcanedev\LaravelTracker\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Migration extends BaseMigration
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Migration constructor.
     */
    public function __construct()
    {
        $this->setConnection(config('laravel-tracker.database.connection', null))
             ->setPrefix(config('laravel-tracker.database.prefix', 'tracker_'));
    }
}
