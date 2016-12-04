<?php

use Arcanedev\LaravelTracker\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateTrackerReferersTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateTrackerReferersTable extends Migration
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
    protected $table = 'referers';

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
            $table->bigInteger('domain_id', false, true)->index();
            $table->string('url')->index();
            $table->string('host');
            $table->string('medium')->nullable()->index();
            $table->string('source')->nullable()->index();
            $table->string('search_terms_hash')->nullable()->index();
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();
        });
    }
}
