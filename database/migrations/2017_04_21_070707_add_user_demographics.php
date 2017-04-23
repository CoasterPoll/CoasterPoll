<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserDemographics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demographics', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('age_range')->nullable()->default(NULL);
            $table->integer('gender')->nullable()->default(NULL);
            $table->string('city')->nullable()->default(NULL);
            $table->decimal('latitude', 10, 6)->nullable()->default(NULL);
            $table->decimal('longitude', 10, 6)->nullable()->default(NULL);
            $table->decimal('park_visits')->nullable()->default(NULL);
            $table->decimal('unique_parks')->nullable()->default(NULL);
            $table->timestamps();
        });

        Schema::table('users', function(Blueprint $table) {
            $table->integer('demographic_id')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demographics');
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('demographic_id');
        });
    }
}
