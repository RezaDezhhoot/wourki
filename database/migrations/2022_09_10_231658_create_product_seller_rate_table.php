<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSellerRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_seller_rate', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_seller_id')->index('product_seller_id');
            $table->integer('user_id')->index('user_id');
            $table->float('rate', 10, 0)->unsigned();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_seller_rate');
    }
}
