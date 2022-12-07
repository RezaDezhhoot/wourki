<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('type', ['home', 'store', 'product', 'service']);
            $table->string('pic');
            $table->unsignedInteger('product_id')->nullable()->index('product_id');
            $table->unsignedInteger('store_id')->nullable()->index('store_id');
            $table->string('alt')->nullable();
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
        Schema::dropIfExists('slider');
    }
}
