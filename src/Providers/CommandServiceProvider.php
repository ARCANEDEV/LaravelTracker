<?php namespace Arcanedev\LaravelTracker\Providers;

use Arcanedev\LaravelTracker\Console;
use Arcanedev\Support\Providers\CommandServiceProvider as ServiceProvider;

/**
 * Class     CommandServiceProvider
 *
 * @package  Arcanesoft\Tracker\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CommandServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        Console\PublishCommand::class,
    ];
}
