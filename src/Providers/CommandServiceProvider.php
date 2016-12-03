<?php namespace Arcanedev\LaravelTracker\Providers;

use Arcanedev\LaravelTracker\Console;
use Arcanedev\Support\ServiceProvider;

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
    protected $commands = [
        Console\PublishCommand::class,
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return $this->commands;
    }
}
