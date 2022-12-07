<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewayTransactionsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_transactions_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('transaction_id')->index('gateway_transactions_logs_transaction_id_foreign');
            $table->string('result_code', 10)->nullable();
            $table->string('result_message')->nullable();
            $table->timestamp('log_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gateway_transactions_logs');
    }
}
