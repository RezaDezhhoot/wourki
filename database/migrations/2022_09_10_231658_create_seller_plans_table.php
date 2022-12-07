<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan_name', 200);
            $table->integer('month_inrterval');
            $table->bigInteger('price');
            $table->text('description');
            $table->enum('status', ['show', 'hide'])->default('show');
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
        Schema::dropIfExists('seller_plans');
    }
}
