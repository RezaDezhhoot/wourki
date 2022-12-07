<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index('users_id');
            $table->unsignedInteger('city_id')->index('region_id');
            $table->text('address');
            $table->string('postal_code', 20)->nullable();
            $table->string('phone_number', 200);
            $table->enum('type', ['home', 'store', 'warehouse']);
            $table->enum('status', ['deleted', 'active'])->default('active');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
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
        Schema::dropIfExists('address');
    }
}
