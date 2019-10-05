<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Usergroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usergroups', function (Blueprint $table) {
            $table->bigIncrements('usergroupid');
            $table->string('title');
            $table->string('opentag');
            $table->string('closetag');
            $table->bigInteger('custom_tag')->default(0);
            $table->string('adminpermissions')->default(0);
            $table->string('staffpermissions')->default(0);
            $table->string('modpermissions')->default(0);
            $table->bigInteger('avatar_height');
            $table->bigInteger('avatar_width');
            $table->integer('immunity');
            $table->string('lastedited');
            $table->bigInteger('features')->default(0);
            $table->bigInteger('editable')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('usergroups');
    }
}
