<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle')->nullable()->default(null);
            $table->string('url');
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => 'Can write content', 'group' => 'Basic', 'description' => "The ability to add/edit/remove static pages."],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
