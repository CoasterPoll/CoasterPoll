<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voter_id');
            $table->integer('link_id')->nullable();
            $table->integer('comment_id')->nullable();
            $table->integer('direction');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_votes');
    }
}
