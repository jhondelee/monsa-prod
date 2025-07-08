<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_order_id')->unsigned();
            $table->string('so_number')->required();
            $table->integer('inventory_id')->required();
            $table->integer('item_id')->unsigned();
            $table->decimal('order_quantity')->nullable()->default(0);
            $table->decimal('unit_cost')->nullable()->default(0);
            $table->decimal('srp')->nullable()->default(0);
            $table->decimal('set_srp')->nullable()->default(0);
            $table->decimal('discount_amount')->nullable()->default(0);
            $table->decimal('discount_percentage')->nullable()->default(0);
            $table->decimal('sub_amount')->nullable()->default(0);

            $table->foreign('sales_order_id')
                  ->references('id')->on('sales_order')->onDelete('cascade');

            $table->foreign('item_id')
                  ->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_items');
    }
}
