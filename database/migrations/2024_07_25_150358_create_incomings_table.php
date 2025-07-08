
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('po_number')->required();
            $table->string('dr_number')->required();
            $table->date('dr_date')->required();
            $table->string('notes',65)->nullable();
            $table->decimal('discount')->default(0.00)->nullable();
            $table->decimal('total_amount')->default(0.00)->required();
            $table->integer('received_by')->required();
            $table->enum('status', array('RECEIVING', 'CLOSED'));
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomings');
    }
}
