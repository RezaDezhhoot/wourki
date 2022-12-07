<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillItemAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_item_attribute', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('bill_item_id')->index('bill_item_id');
            $table->integer('product_attribute_id')->index('product_attribute_id');
            $table->integer('extra_price');
            $table->enum('type', ['رنگ', 'وزن', 'سایز']);
            $table->string('title');
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
        Schema::dropIfExists('bill_item_attribute');
    }
}
