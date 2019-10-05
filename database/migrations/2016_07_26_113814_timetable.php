<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Timetable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable', function (Blueprint $table) {
            $table->bigIncrements('timetableid');
            $table->bigInteger('userid');
            $table->bigInteger('day');
            $table->bigInteger('time');
            /* 0 = radio , 1 = event */
            $table->bigInteger('type');
            $table->bigInteger('perm');
            $table->string('event')->default("");
            $table->integer('activeWeek')->default(1);
            $table->bigInteger('dateline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('timetable');
    }
}
