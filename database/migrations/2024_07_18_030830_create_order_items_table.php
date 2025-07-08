<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->decimal('quantity')->default(0.00)->nullable();
            $table->decimal('unit_cost')->default(0.00)->nullable();
            $table->decimal('unit_total_cost')->default(0.00)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_id')
                  ->references('id')->on('orders');

            $table->foreign('item_id')
                  ->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
