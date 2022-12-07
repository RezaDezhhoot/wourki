<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upgrades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upgradable_id');
            $table->string('upgradable_type');
            $table->integer('upgrade_position_id');
            $table->timestamps();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->string('tracking_code')->nullable();
            $table->enum('pay_type', ['wallet', 'online', 'admin', 'other']);
            $table->integer('price')->default(0);
            $table->string('purchase_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upgrades');
    }
}
