<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerSessionsTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerSessionsTable extends Migration
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
    protected $table = 'sessions';

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
            $table->string('uuid')->unique()->index();
            $table->bigInteger('user_id', false, true)->nullable()->index();
            $table->bigInteger('device_id', false, true)->nullable()->index();
            $table->bigInteger('agent_id', false, true)->nullable()->index();
            $table->bigInteger('geoip_id', false, true)->nullable()->index();
            $table->bigInteger('referer_id', false, true)->nullable()->index();
            $table->bigInteger('cookie_id', false, true)->nullable()->index();
            $table->bigInteger('language_id', false, true)->nullable()->index();
            $table->string('client_ip')->index();
            $table->boolean('is_robot')->default(false);
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();
        });
    }
}
