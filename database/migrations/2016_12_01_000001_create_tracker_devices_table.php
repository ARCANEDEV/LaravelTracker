<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerDevicesTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerDevicesTable extends Migration
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'devices';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Migrate to database.
     */
    public function up()
    {
        $this->createSchema(function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kind', 16)->index();
            $table->string('model', 64)->index();
            $table->string('platform', 64)->index();
            $table->string('platform_version', 16)->index();
            $table->boolean('is_mobile')->default(false);
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();

            $table->unique(['kind', 'model', 'platform', 'platform_version']);
        });
    }
}
