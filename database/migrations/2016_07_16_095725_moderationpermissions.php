<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Moderationpermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moderationpermissions', function (Blueprint $table) {
            $table->bigIncrements('moderationpermissionid');
            $table->bigInteger('forumid');
            $table->bigInteger('usergroupid');
            $table->bigInteger('moderationpermissions')->default(0);
            $table->bigInteger('lastedited')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('moderationpermissions');
    }
}
