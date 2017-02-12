<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimeSlotsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id');
            $table->integer('user_id');
            $table->integer('slot_type');
            $table->integer('day_of_week')->nullable();
            $table->timeTz('slot_time_start');
            $table->timeTz('slot_time_end');
            $table->date('slot_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('local_id')->references('id')->on('locals');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('time_slots');
    }
}
