<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_position', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('ads_position', ['under_last_stores', 'under_best_stores', 'under_wourki_offer', 'under_wourki_discount', 'under_latest_products', 'under_most_viewed_products']);
            $table->string('name', 300);
            $table->unsignedDecimal('price', 10, 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_position');
    }
}
