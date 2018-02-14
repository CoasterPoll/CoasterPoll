<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BallotDoneField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rankings', function($table)
        {
            $table->integer('ballot_complete')->nullable()->default(0);
            $table->integer('poll_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rankings', function($table)
        {
            $table->dropColumn('ballot_complete');
            $table->dropColumn('poll_id');
        });
    }
}
