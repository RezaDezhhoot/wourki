<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsStairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_stairs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ads_id')->index('ads_id');
            $table->text('tracking_code')->nullable();
            $table->string('ref_id', 300)->nullable();
            $table->enum('payment_type', ['wallet', 'online']);
            $table->date('pay_date')->index('pay_datae');
            $table->enum('initial_pay', ['initial', 'stairs']);
            $table->unsignedDecimal('price', 10, 0);
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
        Schema::dropIfExists('ads_stairs');
    }
}
