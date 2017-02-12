<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class Fase2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('clinic_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('clinic_id');
            $table->integer('user_id');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('clinic_id')->references('id')->on('users');
        });

        Schema::create('time_slot_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('time_slot_id');
            $table->integer('health_plan_id')->nullable();
            $table->boolean('private')->default(false);
            $table->integer('slot_count')->default(1);

            $table->timestamps();

            $table->foreign('health_plan_id')->references('id')->on('health_plans');
            $table->foreign('time_slot_id')->references('id')->on('time_slots');

            $table->unique(["time_slot_id", "private", "health_plan_id"]);
        });

        Schema::table('time_slots', function (Blueprint $table) {
            $table->integer('queue_type')->default(1);
        });

        Schema::table('locals', function (Blueprint $table) {
            $table->integer('clinic_id')->nullable();
            $table->foreign('clinic_id')->references('id')->on('users');
        });

        Schema::table('health_plans', function (Blueprint $table) {
            $table->integer('health_plan_id')->nullable();
            $table->foreign('health_plan_id')->references('id')->on('health_plans');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('time_slot_detail_id')->nullable();
            $table->foreign('time_slot_detail_id')->references('id')->on('time_slot_details');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('private_health_plan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("time_slots", function(Blueprint $table) {
            $table->dropColumn('queue_type');
        });

        Schema::table("locals", function(Blueprint $table) {
            $table->dropColumn('clinic_id');
        });

        Schema::table("health_plans", function(Blueprint $table) {
            $table->dropColumn('health_plan_id');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn("time_slot_detail_id");
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('private_health_plan');
        });

        Schema::drop("clinic_user");

        Schema::drop("time_slot_details");
    }
}
