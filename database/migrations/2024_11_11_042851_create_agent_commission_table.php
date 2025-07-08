<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_commission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned()->default(0);
            $table->date('from_date')->required();
            $table->date('to_date')->required();
            $table->decimal('total_sales')->nullable()->default(0);
            $table->integer('created_by')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')
                  ->references('id')->on('employees');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_commission');
    }
}
