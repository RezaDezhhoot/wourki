<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id')->index('store_id');
            $table->integer('user_id')->index('user_id');
            $table->unsignedInteger('address_id')->index('address_id');
            $table->text('address');
            $table->double('customer_lat')->nullable();
            $table->double('customer_lng')->nullable();
            $table->enum('pay_type', ['online', 'postal', 'wallet']);
            $table->string('pay_id', 100)->nullable();
            $table->enum('status', ['pending', 'delivered', 'rejected', 'paid_back', 'approved', 'adminReject'])->default('pending');
            $table->integer('confirmed')->default(0)->comment('0=>unconfirmed , 1 =>confirmed , 2=reject');
            $table->text('reject_reason')->nullable();
            $table->bigInteger('reject_pay_tracking_code')->nullable();
            $table->bigInteger('reject_pay_price')->nullable();
            $table->boolean('reject_pay_type')->nullable();
            $table->date('reject_pay_date')->nullable();
            $table->unsignedInteger('delivery_days');
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
        Schema::dropIfExists('bill');
    }
}
