<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimetableLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_logs', function (Blueprint $table) {
            $table->bigInteger('userid');
            $table->integer('action'); //1 = booked, 2 = unbooked
            $table->integer('type'); //0 = radio, 1 = event
            $table->integer('eventType')->default(0); //only if event
            $table->integer('day');
            $table->integer('time');
            $table->bigInteger('affected_userid');
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
        //
    }
}
