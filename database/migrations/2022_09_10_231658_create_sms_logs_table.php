<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('type', ['bill']);
            $table->integer('message_id');
            $table->text('message');
            $table->integer('status');
            $table->string('status_text', 191);
            $table->string('sender', 191);
            $table->string('receptor', 191);
            $table->unsignedBigInteger('date');
            $table->integer('cost');
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
        Schema::dropIfExists('sms_logs');
    }
}
