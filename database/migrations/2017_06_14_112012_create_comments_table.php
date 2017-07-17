<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->text('body');
            $table->integer('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => "Can comment", 'group' => 'Comments', 'description' => "The ability to comment on stuff."],
            ['name' => "Can moderate comments", 'group' => 'Comments', 'description' => "The ability to moderate comments."],
            ['name' => "Can admin comments", 'group' => 'Comments', 'description' => "The ability to edit all comments."],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
