<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('unit_quantity')->default(0.00)->nullable();
            $table->decimal('onhand_quantity')->default(0.00)->nullable();
            $table->decimal('unit_cost')->default(0.00)->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('received_date')->nullable();
            $table->integer('location')->required();
            $table->enum('status', array('In Stock','Reorder','Critical','Out of Stock'));
            $table->integer('consumable');
            $table->integer('created_by')->required();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('inventory');
    }
}
