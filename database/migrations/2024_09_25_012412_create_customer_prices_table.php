<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->decimal('unit_cost')->default(0.00)->nullable();
            $table->decimal('srp')->default(0.00)->nullable();
            $table->decimal('srp_discounted')->default(0.00)->nullable();
            $table->decimal('percentage_discount')->default(0.00)->nullable();
            $table->tinyInteger('activated_discount')->default(0)->nullable();
            $table->decimal('set_srp')->default(0.00)->nullable();
            $table->timestamps(); 

                $table->foreign('customer_id')
                  ->references('id')->on('customers')->onDelete('cascade');

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
        Schema::dropIfExists('customer_prices');
    }
}
