<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBillItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_item', function (Blueprint $table) {
            $table->foreign(['bill_id'], 'bill_item_ibfk_1')->references(['id'])->on('bill')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'], 'bill_item_ibfk_2')->references(['id'])->on('product_seller')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_item', function (Blueprint $table) {
            $table->dropForeign('bill_item_ibfk_1');
            $table->dropForeign('bill_item_ibfk_2');
        });
    }
}
