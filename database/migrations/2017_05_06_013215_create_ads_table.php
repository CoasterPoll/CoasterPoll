<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('img_url');
            $table->string('img_alt')->nullable()->default(null);
            $table->string('img_href');
            $table->string('sponsor');
            $table->string('sponsor_href');
            $table->integer('campaign_id');
            $table->timestamps();
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => "Can post ads", 'group' => 'Ads', 'description' => "The ability to add/remove/edit ad campaigns and ads."],
            ['name' => "Can view ad details", 'group' => 'Ads', 'description' => "The ability to view analytics from ads."],
            ['name' => "Can sponsor", 'group' => 'Ads', 'description' => "The ability to join the program."],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
