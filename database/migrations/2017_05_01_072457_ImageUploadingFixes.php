<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImageUploadingFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coasters', function(Blueprint $table) {
            $table->string('img_path')->nullable()->default(null);
        });
        Schema::table('manufacturers', function(Blueprint $table) {
            $table->string('img_path')->nullable()->default(null);
        });
        Schema::table('parks', function(Blueprint $table) {
            $table->string('img_path')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coasters', function(Blueprint $table) {
            $table->removeColumn('img_path');
        });
        Schema::table('manufacturers', function(Blueprint $table) {
            $table->removeColumn('img_path');
        });
        Schema::table('parks', function(Blueprint $table) {
            $table->removeColumn('img_path');
        });
    }
}
