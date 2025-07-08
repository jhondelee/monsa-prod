<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_order_id')->unsigned();
            $table->string('so_number',35)->required();
            $table->decimal('sales_total')->nullable()->default(0);
            $table->enum('payment_type', array('Fully Paid','Partial','Credit'));
            $table->enum('payment_status', array('Completed','Existing Balance'));
            $table->integer('created_by')->required();
            $table->integer('updated_by')->required();
            $table->timestamps();

            $table->foreign('sales_order_id')
                  ->references('id')->on('sales_order')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_payment');
    }
}
