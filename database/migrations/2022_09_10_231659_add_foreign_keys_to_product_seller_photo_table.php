<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductSellerPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_seller_photo', function (Blueprint $table) {
            $table->foreign(['seller_product_id'], 'product_seller_photo_ibfk_1')->references(['id'])->on('product_seller')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_seller_photo', function (Blueprint $table) {
            $table->dropForeign('product_seller_photo_ibfk_1');
        });
    }
}
