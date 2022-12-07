<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSellerAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_seller_attribute', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('product_seller_id')->index('product_seller_id');
            $table->integer('attribute_id')->index('attribute_id');
            $table->string('title');
            $table->integer('extra_price')->default(0);
            $table->boolean('deleted')->default(false);
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
        Schema::dropIfExists('product_seller_attribute');
    }
}
