<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerVisitorActivitiesTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerVisitorActivitiesTable extends Migration
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
    protected $table = 'visitor_activities';

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
            $table->unsignedBigInteger('visitor_id')->index();
            $table->unsignedBigInteger('path_id')->nullable()->index();
            $table->unsignedBigInteger('query_id')->nullable()->index();
            $table->unsignedBigInteger('referrer_id')->nullable()->index();
            $table->unsignedBigInteger('route_path_id')->nullable()->index();
            $table->unsignedBigInteger('error_id')->nullable()->index();
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
