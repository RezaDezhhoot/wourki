<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerPlanSubscriptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_plan_subscription_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_id')->index('plan_id');
            $table->unsignedInteger('store_id')->nullable()->index('whole_seller_id');
            $table->integer('user_id')->nullable()->index('user_id');
            $table->unsignedInteger('price');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('pay_id', 100)->nullable();
            $table->string('tracking_code', 300)->nullable();
            $table->unsignedTinyInteger('bazar_in_app_purchase')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->string('purchase_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_plan_subscription_details');
    }
}
