<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',45)->required();
            $table->integer('area_id')->unsigned();
            $table->string('address',65)->nullable();
            $table->string('contact_person',35)->nullable();
            $table->string('contact_number1',35)->nullable();
            $table->string('contact_number2',35)->nullable();
            $table->string('email', 50)->nullable();
            $table->tinyInteger('activated_area_amount')->default(0)->nullable();
            $table->tinyInteger('activated_area_percentage')->default(0)->nullable();
            $table->integer('created_by')->required();
            $table->timestamps(); 

                $table->foreign('area_id')
                  ->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
