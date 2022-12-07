<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content')->nullable();
            $table->string('persian_datetime');
            $table->unsignedInteger('chat_id')->index('private_messages_chat_id_foreign');
            $table->boolean('read')->default(false);
            $table->string('attached_file')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->softDeletes();
            $table->integer('chatable_id')->nullable();
            $table->string('chatable_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('private_messages');
    }
}
