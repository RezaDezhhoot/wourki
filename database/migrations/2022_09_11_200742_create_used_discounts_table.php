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
        Schema::create('used_discounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('discount_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('discount_id')->references('id')->on('discounts')->cascadeOnDelete();
            $table->double('price');
            $table->double('price_with_discount');
            $table->enum('pay_type' , ['wallet' , 'online' , 'other']);
            $table->enum('status' , ['pending' , 'approved'])->default('approved');
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
        Schema::dropIfExists('used_discounts');
    }
};
