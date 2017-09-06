<?php namespace Arcanedev\LaravelTracker\Console;

use Arcanedev\Support\Bases\Command;

/**
 * Class     PublishCommand
 *
 * @package  Arcanedev\LaravelTracker\Console
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PublishCommand extends Command
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-tracker:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the Laravel Tracker config, migrations...';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--provider' => \Arcanedev\LaravelTracker\LaravelTrackerServiceProvider::class,
        ]);
    }
}
