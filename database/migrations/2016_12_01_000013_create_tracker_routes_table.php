<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerRoutesTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerRoutesTable extends Migration
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
    protected $table = 'routes';

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
            $table->string('name')->index();
            $table->string('action')->index();
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();
        });
    }
}
