<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCommissionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_commission_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_commission_id')->unsigned();
            $table->integer('sales_order_id')->unsigned();
            $table->date('so_date')->required();
            $table->string('so_status',15)->nullable();
            $table->integer('sub_agent')->nullable();
            $table->decimal('total_amount')->nullable()->default(0);


            $table->foreign('agent_commission_id')
                  ->references('id')->on('agent_commission')->onDelete('cascade');
                  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_commission_items');
    }
}
