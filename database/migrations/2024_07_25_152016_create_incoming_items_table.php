<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('incoming_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->decimal('received_quantity')->default(0.00)->nullable();
            $table->decimal('unit_cost')->default(0.00)->nullable();
            $table->decimal('unit_total_cost')->default(0.00)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('incoming_id')
                  ->references('id')->on('incomings');

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
        Schema::dropIfExists('incoming_items');
    }
}
