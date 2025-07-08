<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnToSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_to_supplier', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('supplier_id')->unsigned();
            $table->integer('inventory_id')->nullable();
            $table->decimal('return_unit_qty')->nullable()->default(0);
            $table->date('return_date')->nullable();
            $table->tinyInteger('location')->nullable();
            $table->tinyInteger('return_by')->nullable();
            $table->timestamps();


            $table->foreign('item_id')
                  ->references('id')->on('items')->onDelete('cascade');

            $table->foreign('supplier_id')
                  ->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_to_supplier');
    }
}
