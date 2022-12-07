<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('reagent_user_fee');
            $table->integer('reagented_user_fee');
            $table->integer('marketer_fee');
            $table->integer('reagent_user_create_store');
            $table->integer('marketer_user_create_store');
            $table->unsignedDecimal('register_gift', 10, 0);
            $table->text('welcome_msg');
            $table->text('approve_store_msg');
            $table->text('reject_store_msg')->nullable();
            $table->text('new_comment_msg');
            $table->text('checkout_msg');
            $table->text('product_without_photo_msg');
            $table->text('finishing_subscription_plan_message');
            $table->string('app_version');
            $table->integer('ads_expire_days');
            $table->text('wallet_page_help_text')->nullable();
            $table->text('support_page_help_text')->nullable();
            $table->text('ads_page_help_text')->nullable();
            $table->unsignedInteger('excel_export_rows_num');
            $table->unsignedBigInteger('wallet_restriction')->nullable();
            $table->text('chat_rules')->nullable();
            $table->string('no_chat_message')->default('');
            $table->string('no_messages')->default('');
            $table->string('discount_msg');
            $table->string('discount_rial_msg');
            $table->unsignedBigInteger('first_buy_gift')->default(0);
            $table->unsignedBigInteger('first_sell_gift')->default(0);
            $table->string('share_text')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
