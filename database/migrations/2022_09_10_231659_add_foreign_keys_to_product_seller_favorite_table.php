<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductSellerFavoriteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_seller_favorite', function (Blueprint $table) {
            $table->foreign(['user_id'], 'product_seller_favorite_ibfk_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['product_id'], 'product_seller_favorite_ibfk_3')->references(['id'])->on('product_seller')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_seller_favorite', function (Blueprint $table) {
            $table->dropForeign('product_seller_favorite_ibfk_2');
            $table->dropForeign('product_seller_favorite_ibfk_3');
        });
    }
}
