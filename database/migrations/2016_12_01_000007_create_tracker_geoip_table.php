<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerGeoipTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerGeoipTable extends Migration
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
    protected $table = 'geoip';

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
            $table->string('iso_code', 3)->nullable()->index();
            $table->string('country')->nullable()->index();
            $table->string('city', 50)->nullable()->index();
            $table->string('state', 100)->nullable()->index();
            $table->string('state_code', 2)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->double('latitude')->nullable()->index();
            $table->double('longitude')->nullable()->index();
            $table->string('timezone', 20)->nullable();
            $table->string('continent', 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();
        });
    }
}
