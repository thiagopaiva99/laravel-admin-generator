<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTableForDoctors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users", function(Blueprint $table) {
            $table->integer("approval_status")->nullable();
            $table->boolean("preferred_user")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users", function(Blueprint $table) {
            $table->dropColumn("approval_status");
            $table->dropColumn("preferred_user");
        });
    }
}
