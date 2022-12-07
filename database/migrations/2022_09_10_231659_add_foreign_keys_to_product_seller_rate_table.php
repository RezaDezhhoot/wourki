<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductSellerRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_seller_rate', function (Blueprint $table) {
            $table->foreign(['product_seller_id'], 'product_seller_rate_ibfk_1')->references(['id'])->on('product_seller')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'], 'product_seller_rate_ibfk_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_seller_rate', function (Blueprint $table) {
            $table->dropForeign('product_seller_rate_ibfk_1');
            $table->dropForeign('product_seller_rate_ibfk_2');
        });
    }
}
