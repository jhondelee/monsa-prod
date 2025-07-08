<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('returns_id')->required();
            $table->integer('item_id')->unsigned();
            $table->decimal('item_quantity')->nullable()->default(0);
            $table->decimal('return_quantity')->nullable()->default(0);
            $table->decimal('unit_cost')->nullable()->default(0);
            $table->decimal('srp')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_items');
    }
}
