<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_seller', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 300);
            $table->text('description');
            $table->unsignedDecimal('price', 65, 0);
            $table->integer('discount')->default(0);
            $table->integer('quantity')->nullable();
            $table->unsignedTinyInteger('visible')->default(1);
            $table->unsignedInteger('category_id')->index('category_id');
            $table->unsignedInteger('store_id')->index('store_id');
            $table->enum('status', ['approved', 'rejected', 'deleted', 'pending'])->default('pending');
            $table->integer('hint')->default(0);
            $table->integer('is_vip')->default(0);
            $table->unsignedTinyInteger('product_without_photo_notified')->default(0);
            $table->unsignedTinyInteger('guarantee_mark')->default(0);
            $table->unsignedDecimal('shipping_price_to_tehran', 10, 0);
            $table->unsignedDecimal('shipping_price_to_other_towns', 10, 0);
            $table->unsignedInteger('deliver_time_in_tehran');
            $table->unsignedInteger('deliver_time_in_other_towns');
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
        Schema::dropIfExists('product_seller');
    }
}
