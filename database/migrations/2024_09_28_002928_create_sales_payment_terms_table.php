<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesPaymentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_payment_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_payment_id')->unsigned();
            $table->date('date_payment')->required();
            $table->integer('payment_mode_id')->unsigned();
            $table->string('trasanction_no',35)->nullable();
            $table->string('bank_name',45)->nullable();
            $table->string('bank_account_no',45)->nullable();
            $table->string('bank_account_name',45)->nullable();
            $table->decimal('amount_collected')->nullable()->default(0);
            $table->integer('collected_by')->nullable();
            $table->integer('created_by')->nullable();

            $table->foreign('sales_payment_id')
                  ->references('id')->on('sales_payment')->onDelete('cascade');

            $table->foreign('payment_mode_id')
                  ->references('id')->on('mode_of_payments')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_payment_terms');
    }
}
