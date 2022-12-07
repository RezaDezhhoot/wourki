<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductSellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_seller', function (Blueprint $table) {
            $table->foreign(['store_id'], 'product_seller_ibfk_2')->references(['id'])->on('store')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['category_id'], 'product_seller_ibfk_3')->references(['id'])->on('category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_seller', function (Blueprint $table) {
            $table->dropForeign('product_seller_ibfk_2');
            $table->dropForeign('product_seller_ibfk_3');
        });
    }
}
