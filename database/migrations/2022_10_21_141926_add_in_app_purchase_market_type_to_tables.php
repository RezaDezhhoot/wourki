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
        Schema::table('seller_plan_subscription_details', function (Blueprint $table) {
            $table->enum('in_app_purchase_market_type' , ['bazaar' , 'myket'])->nullable();
        });
        Schema::table('upgrades', function (Blueprint $table) {
            $table->enum('in_app_purchase_market_type', ['bazaar', 'myket'])->nullable();
        });
        Schema::table('ads', function (Blueprint $table) {
            $table->string('purchase_token')->nullable();
            $table->enum('in_app_purchase_market_type', ['bazaar', 'myket'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
