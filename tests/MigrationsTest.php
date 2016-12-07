<?php namespace Arcanedev\LaravelTracker\Tests;

use Illuminate\Support\Facades\Schema;

/**
 * Class     MigrationsTest
 *
 * @package  Arcanedev\LaravelTracker\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MigrationsTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_publish_migrations()
    {
        /** @var \Illuminate\Filesystem\Filesystem $filesystem */
        $filesystem = $this->app['files'];
        $src        = $this->getMigrationsSrcPath();
        $dest       = $this->getMigrationsDestPath();

        $this->assertCount(0, $filesystem->allFiles($dest));

        $this->publishMigrations();

        $this->assertEquals(
            count($filesystem->allFiles($src)),
            count($filesystem->allFiles($dest))
        );

        $filesystem->cleanDirectory($dest);
    }

    /** @test */
    public function it_can_migrate()
    {
        $this->migrate();

        foreach ($this->getTablesNames() as $table) {
            $this->assertTrue(Schema::hasTable($table), "The table [$table] not found in the database.");
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function getTablesNames($prefix = 'tracker_')
    {
        $tables = array_map(function ($table) use ($prefix) {
            return $prefix.$table;
        }, [
            'agents',
            'cookies',
            'devices',
            'domains',
            'geoip',
            'languages',
            'paths',
            'queries',
            'referers',
            'referer_search_terms',
            'routes',
            'route_paths',
            'route_path_parameters',
            'sessions',
            'session_activities',
        ]);

        return $tables + [
            // Fixture tables
            'users',
        ];
    }
}
