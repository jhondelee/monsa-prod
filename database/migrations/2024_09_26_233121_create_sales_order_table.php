<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('so_number')->required();
            $table->date('so_date')->required();
            $table->string('remarks',65)->nullable();
            $table->integer('customer_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->integer('sub_employee_id')->nullable();
            $table->decimal('unit_cost_total')->nullable()->default(0);
            $table->decimal('total_sales')->nullable()->default(0);
            $table->decimal('total_amount_discount')->nullable()->default(0);
            $table->decimal('total_percent_discount')->nullable()->default(0);
            $table->integer('location')->required();
            $table->integer('inventory_deducted')->nullable()->default(0);
            $table->integer('approved_by')->nullable();
            $table->integer('created_by')->required();
            $table->integer('updated_by')->nullable();
            $table->enum('status', array('NEW','POSTED', 'CLOSED','CANCELED'));
            $table->timestamps();

            $table->foreign('customer_id')
                  ->references('id')->on('customers');

            $table->foreign('employee_id')
                  ->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_order');
    }
}
