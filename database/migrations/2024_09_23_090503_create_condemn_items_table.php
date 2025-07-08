<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCondemnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condemn_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('condemn_id')->unsigned();
            $table->integer('inventory_id')->unsigned();
            $table->string('source',45)->required();
            $table->integer('item_id')->unsigned();
            $table->decimal('unit_quantity')->default(0.00)->nullable();
            $table->decimal('unit_cost')->default(0.00)->nullable();
            $table->timestamps(); 


                $table->foreign('condemn_id')
                  ->references('id')->on('condemn')->onDelete('cascade');
                   
                $table->foreign('item_id')
                  ->references('id')->on('items')->onDelete('cascade');

                $table->foreign('inventory_id')
                  ->references('id')->on('inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('condemn_items');
    }
}
