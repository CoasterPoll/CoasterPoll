<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharingLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_links', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->integer('state')->default(1);
            $table->text('body')->nullable();
            $table->string('link')->nullable()->default(null);
            $table->integer('posted_by')->unsigned()->nullable();
            $table->integer('linkable_id')->unsigned()->nullable();
            $table->string('linkable_type')->nullable()->default(null);
            $table->integer('comment_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('posted_by')->references('id')->on('users')->onDelete('SET NULL');
        });

        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => "Can edit links", 'group' => 'Links', 'description' => "The ability to add/remove/edit other peoples links."],
            ['name' => "Can submit links", 'group' => 'Links', 'description' => "The ability to submit/edit one's own links."],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_links');
    }
}
