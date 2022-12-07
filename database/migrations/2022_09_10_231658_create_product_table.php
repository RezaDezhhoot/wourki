<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name')->fulltext('name');
            $table->string('slug', 300);
            $table->text('description');
            $table->integer('discount')->nullable()->default(0)->comment('takhfif');
            $table->integer('quantity')->comment('tedad');
            $table->integer('visible')->nullable()->default(1);
            $table->integer('deleted')->default(0);
            $table->unsignedInteger('subcatid')->index('subcatid');
            $table->integer('hits')->default(0);
            $table->decimal('price', 65, 0);
            $table->integer('is_vip')->nullable()->default(0);
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
        Schema::dropIfExists('product');
    }
}
