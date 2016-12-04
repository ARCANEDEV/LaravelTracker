<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerCookiesTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerCookiesTable extends Migration
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
    protected $table = 'cookies';

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
            $table->string('uuid')->unique();
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();
        });
    }
}
