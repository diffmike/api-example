<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('shop_id');
            
            $table->string('title');
            $table->string('vendor_code')->nullable();
            $table->float('price')->defalut(0);
            $table->float('price_with_discount')->defalut(0);
            $table->unsignedSmallInteger('discount')->defalut(0);
            $table->string('trademark')->nullable();
            $table->float('weight')->nullable();
            $table->string('unit')->nullable();
            $table->string('structure')->nullable();
            $table->text('description')->nullable();
            $table->float('proteins')->nullable();
            $table->float('fats')->nullable();
            $table->float('carbohydrates')->nullable();
            $table->float('calories')->nullable();
            
            $table->timestamps();
            
            $table->foreign('shop_id', 'f_shop_products')->references('id')->on('shops')->onDelete('cascade');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('f_shop_products');
        });
        Schema::dropIfExists('products');
    }
}
