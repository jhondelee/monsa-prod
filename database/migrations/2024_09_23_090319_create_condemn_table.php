<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCondemnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condemn', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no',25)->required();
            $table->date('condemn_date')->required();
            $table->tinyInteger('location')->default(0);
            $table->string('reason',60)->required();
            $table->tinyInteger('status')->default(0);
            $table->integer('approved_by')->required();
            $table->integer('created_by')->required();
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
        Schema::dropIfExists('condemn');
    }
}
