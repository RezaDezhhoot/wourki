<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->double('cost');
            $table->enum('wallet_type', ['input', 'output', 'reagent', 'reagented_create_store', 'reagented', 'date_gift', 'buy_gift', 'register_gift', 'reject_order', 'buy_ad', 'buy_plan', 'first_buy_gift', 'first_sell_gift', 'upgrade_product', 'upgrade_store']);
            $table->string('tracking_code')->nullable();
            $table->unsignedInteger('payable')->default(0);
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('wallet');
    }
}
