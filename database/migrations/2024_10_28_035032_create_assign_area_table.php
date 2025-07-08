<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_area', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned()->default(0);
            $table->integer('area_id')->unsigned();
            $table->integer('rate_id')->unsigned();
            $table->integer('created_by')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')
                  ->references('id')->on('employees');
                  
            $table->foreign('area_id')
                  ->references('id')->on('areas');

            $table->foreign('rate_id')
                  ->references('id')->on('commission_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assign_area');
    }
}
