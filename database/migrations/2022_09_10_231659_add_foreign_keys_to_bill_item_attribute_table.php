<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBillItemAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_item_attribute', function (Blueprint $table) {
            $table->foreign(['bill_item_id'], 'bill_item_attribute_ibfk_1')->references(['id'])->on('bill_item')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_attribute_id'], 'bill_item_attribute_ibfk_2')->references(['id'])->on('product_seller_attribute')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_item_attribute', function (Blueprint $table) {
            $table->dropForeign('bill_item_attribute_ibfk_1');
            $table->dropForeign('bill_item_attribute_ibfk_2');
        });
    }
}
