<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_photo', function (Blueprint $table) {
            
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->Increments('id');
            $table->string('name',35)->required();
            $table->string('remarks',45)->nullable();
            $table->string('picture',60)->required();
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
        Schema::dropIfExists('event_photo');
    }
}
