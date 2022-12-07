<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_chat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('report_chat_user_id_foreign');
            $table->unsignedInteger('chat_id')->index('report_chat_chat_id_foreign');
            $table->text('text');
            $table->timestamps();
            $table->boolean('seen')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_chat');
    }
}
