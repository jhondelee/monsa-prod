<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('area_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->decimal('unit_cost')->default(0.00)->nullable();
            $table->decimal('srp')->default(0.00)->nullable();
            $table->decimal('srp_added')->default(0.00)->nullable();
            $table->decimal('percentage_added')->default(0.00)->nullable();
            $table->tinyInteger('activated_added')->default(0)->nullable();
            $table->decimal('set_srp')->default(0.00)->nullable();
            $table->timestamps(); 

                $table->foreign('area_id')
                  ->references('id')->on('areas')->onDelete('cascade');

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
        Schema::dropIfExists('area_prices');
    }
}
