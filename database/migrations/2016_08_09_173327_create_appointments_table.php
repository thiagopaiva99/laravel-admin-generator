<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppointmentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('time_slot_id');
            $table->integer('time_slot_local_id');
            $table->integer('time_slot_user_id');
            $table->integer('user_id');
            $table->dateTimeTz('appointment_start');
            $table->dateTimeTz('appointment_end');

            $table->string("patient_name", 60)->nullable();
            $table->string("patient_phone", 20)->nullable();
            $table->string("patient_email", 255)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('time_slot_id')->references('id')->on('time_slots');
            $table->foreign('time_slot_local_id')->references('id')->on('locals');
            $table->foreign('time_slot_user_id')->references('id')->on('users');

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
        Schema::drop('appointments');
    }
}
