<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClosedDatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closed_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTimeTz('start_datetime');
            $table->dateTimeTz('end_datetime');
            $table->integer('user_id');
            $table->integer('local_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('local_id')->references('id')->on('locals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('closed_dates');
    }
}
