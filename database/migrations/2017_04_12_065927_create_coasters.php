<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCoasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ## Objects
        Schema::create('coasters', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('park_id')->unsigned();
            $table->integer('manufacturer_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->string('rcdb_id');
            $table->string('slug');
            $table->text('copyright');
            $table->string('img_url');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rankings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('coaster_id')->unsigned();
            $table->integer('user_id');
            $table->integer('rank');
            $table->timestamps();
        });

        Schema::create('parks', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short')->unique();
            $table->string('city');
            $table->string('country');
            $table->string('website');
            $table->string('rcdb_id');
            $table->string('img_url');
            $table->text('copyright');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('types', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('types')->insert([
            ['id' => 1, 'name' => 'Wood', 'created_at' => \Carbon\Carbon::now()],
            ['id' => 2, 'name' => 'Steel', 'created_at' => \Carbon\Carbon::now()],
            ['id' => 3, 'name' => 'Hybrid', 'created_at' => \Carbon\Carbon::now()]
        ]);

        Schema::create('manufacturers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('abbreviation')->unique();
            $table->string('website');
            $table->string('rcdb_id');
            $table->text('copyright');
            $table->string('location');
            $table->string('img_url');
            $table->timestamps();
            $table->softDeletes();
        });

        // ## Relationships
        Schema::create('category_coaster', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('coaster_id');
            $table->integer('category_id');
            $table->timestamps();
        });

        Schema::create('coaster_user', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('coaster_id');
            $table->integer('user_id');
            $table->timestamps();
        });

        // ## Relavent permissions
        DB::table('permissions')->insert([
            ['name' => 'Can manage coasters', 'group' => 'Coasters', 'description' => "The ability to add/edit/remove coasters, parks, and manufacturers."],
            ['name' => 'Can track coasters', 'group' => 'Coasters', 'description' => "The ability to track coasters that have been ridden."],
            ['name' => 'Can rank coasters', 'group' => 'Coasters', 'description' => "The ability to rank coasters that have been ridden."],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coasters');
        Schema::dropIfExists('rankings');
        Schema::dropIfExists('parks');
        Schema::dropIfExists('types');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('manufacturers');
        Schema::dropIfExists('category_coaster');
        Schema::dropIfExists('coaster_user');

        DB::table('permissions')->where('name', "Can manage coasters")->delete();
    }
}
