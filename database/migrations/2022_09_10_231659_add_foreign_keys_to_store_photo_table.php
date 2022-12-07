<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStorePhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_photo', function (Blueprint $table) {
            $table->foreign(['store_id'], 'store_photo_ibfk_1')->references(['id'])->on('store')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_photo', function (Blueprint $table) {
            $table->dropForeign('store_photo_ibfk_1');
        });
    }
}
