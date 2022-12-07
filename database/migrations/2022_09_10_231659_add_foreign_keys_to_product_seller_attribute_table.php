<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductSellerAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_seller_attribute', function (Blueprint $table) {
            $table->foreign(['attribute_id'], 'product_seller_attribute_ibfk_1')->references(['id'])->on('attribute')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['product_seller_id'], 'product_seller_attribute_ibfk_2')->references(['id'])->on('product_seller')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_seller_attribute', function (Blueprint $table) {
            $table->dropForeign('product_seller_attribute_ibfk_1');
            $table->dropForeign('product_seller_attribute_ibfk_2');
        });
    }
}
