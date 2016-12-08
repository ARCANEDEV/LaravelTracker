<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerSessionActivitiesTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerSessionActivitiesTable extends Migration
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
    protected $table = 'session_activities';

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
            $table->bigInteger('session_id', false, true)->index();
            $table->bigInteger('path_id', false, true)->nullable()->index();
            $table->bigInteger('query_id', false, true)->nullable()->index();
            $table->bigInteger('referrer_id', false, true)->nullable()->index();
            $table->bigInteger('route_path_id', false, true)->nullable()->index();
            $table->bigInteger('error_id', false, true)->nullable()->index();
            $table->string('method', 10)->index();
            $table->boolean('is_ajax')->default(false);
            $table->boolean('is_secure')->default(false);
            $table->boolean('is_json')->default(false);
            $table->boolean('wants_json')->default(false);
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();
        });
    }
}
