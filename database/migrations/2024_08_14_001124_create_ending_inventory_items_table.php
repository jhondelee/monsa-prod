<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndingInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ending_inventory_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ending_inventory_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('unit_quantity')->default(0.00)->nullable();
            $table->decimal('onhand_quantity')->default(0.00)->nullable();
            $table->decimal('unit_cost')->default(0.00)->nullable();
            $table->date('received_date')->nullable();
            $table->integer('location')->required();
            $table->softDeletes();
            $table->timestamps();

        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ending_inventory_items');
    }
}
