<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index('users_id');
            $table->text('slogan');
            $table->unsignedInteger('address_id')->index('address_id');
            $table->integer('guild_id')->index('guild_id');
            $table->text('slug')->nullable();
            $table->string('name', 200)->fulltext('name');
            $table->string('user_name', 100)->unique('user_name');
            $table->integer('min_pay')->default(0);
            $table->enum('status', ['approved', 'rejected', 'pending', 'deleted'])->default('pending');
            $table->unsignedTinyInteger('visible')->default(1);
            $table->text('about');
            $table->string('phone_number', 200);
            $table->string('telegram_address', 200)->nullable();
            $table->string('instagram_address', 200)->nullable();
            $table->enum('phone_number_visibility', ['show', 'hide'])->default('show');
            $table->enum('telegram_channel_visibility', ['show', 'hide'])->default('show');
            $table->enum('mobile_visibility', ['show', 'hide'])->default('show');
            $table->enum('instagram_visibility', ['show', 'hide'])->default('show');
            $table->text('reject_reason')->nullable();
            $table->unsignedInteger('total_hits')->default(0);
            $table->enum('pay_type', ['online', 'postal', 'both'])->default('postal');
            $table->enum('store_type', ['service', 'product'])->default('product');
            $table->enum('activity_type', ['country', 'province'])->default('country');
            $table->string('shaba_code', 26)->nullable();
            $table->unsignedTinyInteger('notified_finishing_subscription_plan')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->boolean('gift_assigned_to_refferer')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store');
    }
}
