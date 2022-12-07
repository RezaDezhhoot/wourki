<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('market_id');
            $table->unsignedInteger('product_id');
            $table->foreign('market_id')->references('id')->on('store')->onDelete('CASCADE');
            $table->foreign('product_id')->references('id')->on('product_seller')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_product');
    }
};
