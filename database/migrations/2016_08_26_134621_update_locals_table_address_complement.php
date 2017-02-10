<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLocalsTableAddressComplement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("locals", function(Blueprint $table) {
            $table->string("address_complement", 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("locals", function(Blueprint $table) {
            $table->dropColumn("address_complement");
        });
    }
}
