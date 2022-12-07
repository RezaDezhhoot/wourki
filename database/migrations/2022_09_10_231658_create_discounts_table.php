<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('code');
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('discountable_type', ['guild', 'category', 'service', 'product', 'store', 'all', 'all-product', 'all-service', 'all-ads', 'all-plans', 'ad', 'plan', 'upgrade', 'all-upgrade', 'all-sending', 'store-sending', 'product-sending']);
            $table->unsignedInteger('discountable_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('percentage');
            $table->timestamps();
            $table->enum('type', ['percentage', 'rial']);
            $table->unsignedInteger('min_price');
            $table->unsignedInteger('max_price');
            $table->boolean('admin_made')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
