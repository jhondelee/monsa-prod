<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryMovementItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_movement_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_movement_id')->unsigned();
            $table->integer('inventory_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->decimal('quantity')->default(0.00)->nullable();
            $table->string('from_locaton',30)->required();
            $table->string('to_location',30)->required();
            $table->integer('posted');
            $table->timestamps();

                $table->foreign('item_id')
                  ->references('id')->on('items');

                $table->foreign('inventory_id')
                  ->references('id')->on('inventory');

                $table->foreign('inventory_movement_id')
                  ->references('id')->on('inventory_movement');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_movement_items');
    }
}
