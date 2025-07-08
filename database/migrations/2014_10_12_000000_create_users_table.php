<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash_id');
            $table->string('email', 50)->unique();
            $table->string('username', 50)->unique();            
            $table->string('password', 225);
            $table->tinyInteger('failed_login_attempts')->default(0);
            $table->dateTime('failed_login_time')->nullable();
            $table->tinyInteger('activated')->nullable()->default(0);            
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->integer('parent_id')->after('id')->unsigned();
            $table->integer('updated_by')->after('deleted_at')->unsigned();
            $table->integer('created_by')->after('updated_by')->unsigned();
            
            $table->foreign('parent_id')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
