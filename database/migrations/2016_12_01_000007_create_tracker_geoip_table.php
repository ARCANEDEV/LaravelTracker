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
            $table->double('latitude')->nullable()->index();
            $table->double('longitude')->nullable()->index();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('country_code3', 3)->nullable()->index();
            $table->string('country_name')->nullable()->index();
            $table->string('region', 2)->nullable();
            $table->string('city', 50)->nullable()->index();
            $table->string('postal_code', 20)->nullable();
            $table->bigInteger('area_code')->nullable();
            $table->double('dma_code')->nullable();
            $table->double('metro_code')->nullable();
            $table->string('continent_code', 2)->nullable();
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();
        });
    }
}
