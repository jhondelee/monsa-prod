<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {

            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->Increments('id');
            $table->string('code',15)->unique()->required();
            $table->string('name',35)->required();
            $table->string('description',60)->nullable();
            $table->integer('unit_id')->unsigned();
            $table->integer('unit_quantity')->default(0);
            $table->integer('safety_stock_level');
            $table->integer('criticl_stock_level');
            $table->decimal('srp')->default(0.00);
            $table->decimal('unit_cost')->default(0.00);
            $table->string('picture',60)->nullable();
            $table->string('created_by',45)->required();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('unit_id')
                  ->references('id')->on('unit_of_measure');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
