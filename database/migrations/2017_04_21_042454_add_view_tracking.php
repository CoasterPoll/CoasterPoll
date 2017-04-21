<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddViewTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('views', function(Blueprint $table) {
            $table->increments('id');
            $table->string('page');
            $table->float('time')->nullable()->default(0);
            $table->integer('user_id')->nullable();
            $table->string('query')->nullable();
            $table->string('hash')->nullable();
            $table->string('referrer')->nullable();
            $table->string('session')->nullable();
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
        Schema::dropIfExists('views');
    }
}
