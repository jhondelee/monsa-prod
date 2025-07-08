<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no')->required();
            $table->string('so_number')->required();
            $table->date('return_date')->required();
            $table->string('reason',65)->nullable();
            $table->integer('location')->required();
            $table->tinyInteger('status')->default(0);
            $table->integer('received_by')->required();
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
        Schema::dropIfExists('returns');
    }
}
