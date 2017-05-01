<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group');
            $table->integer('coaster_id');
            $table->decimal('percentage');
            $table->integer('wins');
            $table->integer('losses');
            $table->integer('ties');
            $table->integer('above');
            $table->integer('below');
            $table->integer('equal');
            $table->string('flags')->nullable()->default(null);
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => 'Can run results', 'group' => 'Coasters', 'description' => "The ability to create the results pages."],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
