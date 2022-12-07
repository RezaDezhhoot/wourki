<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_documents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('balance', 10, 0);
            $table->string('description', 512);
            $table->enum('type', ['bill', 'plan', 'checkout', 'marketer', 'wallet', 'upgrade', 'ad']);
            $table->unsignedInteger('bill_id')->nullable()->default(0)->index('accounting_documents_ibfk_1');
            $table->integer('wallet_id')->nullable()->default(0);
            $table->integer('ads_id')->nullable()->default(0);
            $table->unsignedInteger('plan_id')->nullable()->default(0)->index('accounting_documents_ibfk_2');
            $table->unsignedInteger('checkout_id')->nullable()->default(0);
            $table->bigInteger('upgrade_id')->default(0);
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
        Schema::dropIfExists('accounting_documents');
    }
}
