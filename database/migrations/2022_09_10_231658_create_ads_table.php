<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ads_position_id')->index('ads_position_id');
            $table->string('pic', 300);
            $table->string('final_pic', 300)->nullable();
            $table->enum('link_type', ['store', 'product']);
            $table->unsignedInteger('product_id')->nullable()->index('product_id');
            $table->unsignedInteger('store_id')->nullable()->index('store_id');
            $table->text('description');
            $table->enum('pay_status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('payment_type', ['wallet', 'online'])->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'deleted'])->default('pending');
            $table->date('expire_date')->nullable();
            $table->integer('user_id')->index('user_id');
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
        Schema::dropIfExists('ads');
    }
}
