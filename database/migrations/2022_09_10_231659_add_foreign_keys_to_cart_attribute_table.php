<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCartAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_attribute', function (Blueprint $table) {
            $table->foreign(['cart_id'], 'cart_attribute_ibfk_1')->references(['id'])->on('cart')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_seller_attribute_id'], 'cart_attribute_ibfk_2')->references(['id'])->on('product_seller_attribute')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_attribute', function (Blueprint $table) {
            $table->dropForeign('cart_attribute_ibfk_1');
            $table->dropForeign('cart_attribute_ibfk_2');
        });
    }
}
