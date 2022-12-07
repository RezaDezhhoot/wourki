<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewayTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('port', ['MELLAT', 'JAHANPAY', 'PARSIAN', 'PASARGAD', 'PAYLINE', 'SADAD', 'ZARINPAL', 'SAMAN', 'ASANPARDAKHT', 'PAYPAL']);
            $table->decimal('price', 15);
            $table->string('ref_id', 100)->nullable();
            $table->string('tracking_code', 50)->nullable();
            $table->string('card_number', 50)->nullable();
            $table->enum('status', ['INIT', 'SUCCEED', 'FAILED'])->default('INIT');
            $table->string('ip', 20)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gateway_transactions');
    }
}
