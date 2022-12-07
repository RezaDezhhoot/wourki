<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guild', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 200);
            $table->string('pic', 200)->nullable();
            $table->enum('guild_type', ['product', 'service'])->default('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guild');
    }
}
