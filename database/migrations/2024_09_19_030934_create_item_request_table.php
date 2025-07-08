<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_request', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id')->unsigned();
            $table->string('reference_no',30)->required();
            $table->integer('item_id')->unsigned();
            $table->decimal('request_qty')->default(0.00)->nullable();
            $table->integer('posted');
            $table->integer('created_by');
            $table->timestamps();

                $table->foreign('inventory_id')
                  ->references('id')->on('inventory');

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
        Schema::dropIfExists('item_request');
    }
}
