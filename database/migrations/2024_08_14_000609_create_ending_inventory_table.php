<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndingInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ending_inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('ending_date')->required();
            $table->integer('prepared_by')->required();
            $table->enum('status', array('POSTED', 'UNPOSTED'));
            $table->softDeletes();
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
        Schema::dropIfExists('ending_inventory');
    }
}
