<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerVisitorsTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerVisitorsTable extends Migration
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
    protected $table = 'visitors';

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
            $table->string('uuid')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->unsignedBigInteger('agent_id')->nullable()->index();
            $table->unsignedBigInteger('geoip_id')->nullable()->index();
            $table->unsignedBigInteger('referer_id')->nullable()->index();
            $table->unsignedBigInteger('cookie_id')->nullable()->index();
            $table->unsignedBigInteger('language_id')->nullable()->index();
            $table->string('client_ip')->index();
            $table->boolean('is_robot')->default(false);
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();

            $table->unique('uuid');
        });
    }
}
