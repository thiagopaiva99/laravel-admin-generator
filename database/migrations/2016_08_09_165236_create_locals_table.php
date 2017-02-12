<?php

use Illuminate\Database\Migrations\Migration;

/* Substituindo pelo Blueprint da biblioteca do PostGIS */
use Phaza\LaravelPostgis\Schema\Blueprint;

class CreateLocalsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('address', 255)->nullable();
            $table->string('image_src', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->float('amount', 11 ,2)->nullable();
            $table->integer('appointment_duration_in_minutes')->default(20);
            $table->point('location'); // ponto do tipo geográfico!
            $table->integer('user_id'); // foreign key
            $table->timestamps();
            $table->softDeletes();

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
        Schema::drop('locals');
    }
}
