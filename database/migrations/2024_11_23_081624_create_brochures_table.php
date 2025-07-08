<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrochuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brochures', function (Blueprint $table) {
            
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->Increments('id');
            $table->string('name',35)->required();
            $table->string('remarks',45)->nullable();
            $table->string('docs',60)->required();
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
        Schema::dropIfExists('brochures');
    }
}
