<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollectedStatusToSalesPaymentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_payment_terms', function (Blueprint $table) {
            $table->enum('status', array('Pending','Complete', 'Redep ','Pull Out'))->after('amount_collected');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_payment_terms', function (Blueprint $table) {
               $table->dropColumn('status');
        });
    }
}


