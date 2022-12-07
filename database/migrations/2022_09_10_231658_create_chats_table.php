<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id')->nullable()->index('chats_sender_id_foreign');
            $table->integer('receiver_id')->nullable()->index('chats_receiver_id_foreign');
            $table->integer('chatable_id')->nullable();
            $table->string('chatable_name');
            $table->softDeletes();
            $table->enum('blocked_by', ['sender', 'receiver'])->nullable();
            $table->boolean('blocked_by_sender')->default(false);
            $table->boolean('blocked_by_receiver')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
