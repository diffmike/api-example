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
    
            $table->unsignedInteger('shop_id');
    
            $table->string('title');
            $table->date('start');
            $table->date('finish');
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            
            $table->timestamps();
            
            $table->foreign('shop_id', 'f_shop_campaigns')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign('f_shop_campaigns');
        });
        Schema::dropIfExists('campaigns');
    }
}
