<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('mobile', 50)->unique('mobile');
            $table->integer('mobile_confirmed')->default(0);
            $table->string('verify_mobile_token', 100)->nullable();
            $table->string('thumbnail_photo')->nullable();
            $table->text('about')->nullable();
            $table->string('shaba_code', 30)->nullable();
            $table->string('card', 16)->nullable();
            $table->string('gcm_code')->nullable();
            $table->string('reset_password_token')->nullable();
            $table->string('email')->nullable();
            $table->text('remember_token')->nullable();
            $table->boolean('returnPayType')->default(true)->comment('0 => bank, 1=>wallet');
            $table->integer('verify_forget_password_token')->nullable();
            $table->unsignedTinyInteger('banned')->default(0);
            $table->string('reagent_code', 50)->nullable()->index('reagent_code');
            $table->unsignedTinyInteger('become_marketer')->default(0);
            $table->dateTime('last_login_datetime')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->integer('referrer_user_id')->nullable()->index('referrer_user_id');
            $table->boolean('chats_blocked')->default(false);
            $table->dateTime('last_chat_visit_datetime')->default('2022-08-04 18:55:34');
            $table->enum('register_from', ['website', 'android'])->nullable();

            $table->fullText(['first_name', 'last_name'], 'first_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
