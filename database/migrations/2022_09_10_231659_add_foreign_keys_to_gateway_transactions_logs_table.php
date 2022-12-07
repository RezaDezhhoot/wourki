<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGatewayTransactionsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gateway_transactions_logs', function (Blueprint $table) {
            $table->foreign(['transaction_id'])->references(['id'])->on('gateway_transactions')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gateway_transactions_logs', function (Blueprint $table) {
            $table->dropForeign('gateway_transactions_logs_transaction_id_foreign');
        });
    }
}
