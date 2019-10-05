<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Forumpermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forumpermissions', function (Blueprint $table) {
            $table->bigIncrements('forumpermissionid');
            $table->bigInteger('forumid');
            $table->bigInteger('usergroupid');
            $table->bigInteger('forumpermissions')->default(0);
            $table->bigInteger('lastedited')->default();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forumpermissions');
    }
}
