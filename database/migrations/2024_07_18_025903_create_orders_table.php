<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_number')->required();
            $table->date('po_date')->required();
            $table->string('remarks',65)->nullable();
            $table->integer('supplier_id')->unsigned();
            $table->decimal('discount')->nullable();
            $table->decimal('grand_total')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('created_by')->required();
            $table->integer('updated_by')->nullable();
            $table->enum('status', array('NEW','POSTED', 'CLOSED','CANCELED'));
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('supplier_id')
                  ->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
