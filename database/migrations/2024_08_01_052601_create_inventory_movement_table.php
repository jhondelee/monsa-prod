<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryMovementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_movement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reference_no')->required();
            $table->string('source',30)->required();
            $table->string('destination',30)->required();
            $table->string('notes',45)->nullable();
            $table->date('transfer_date')->nullable();
            $table->string('created_by')->nullable();
            $table->enum('status', array('CREATED','POSTED','REJECTED'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_movement');
    }
}
