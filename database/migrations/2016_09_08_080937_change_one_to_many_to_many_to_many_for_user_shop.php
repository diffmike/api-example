<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOneToManyToManyToManyForUserShop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_shop_id_foreign');
            $table->dropColumn('shop_id');
        });
        
        Schema::dropIfExists('company_user');
        
        Schema::create('shop_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('shop_id');
            $table->primary(['shop_id', 'user_id']);
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_user');
    }
}
