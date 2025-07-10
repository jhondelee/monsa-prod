<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostDatedToSalesPaymentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_payment_terms', function (Blueprint $table) {
            $table->date('post_dated')->nullable()->after('trasanction_no');
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
            $table->dropColumn('post_dated');
        });
    }
}
