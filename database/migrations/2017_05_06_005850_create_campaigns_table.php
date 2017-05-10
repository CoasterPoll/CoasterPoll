<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('start_at')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('end_at')->nullable()->default(null);
            $table->decimal('cost', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->decimal('budget', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('campaign_user', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('campaign_user');
    }
}
