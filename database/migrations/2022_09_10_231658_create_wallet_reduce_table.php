<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletReduceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_reduce', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ReducedItem');
            $table->unsignedBigInteger('ReducedFrom');
            $table->unsignedBigInteger('Amount');
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
        Schema::dropIfExists('wallet_reduce');
    }
}
