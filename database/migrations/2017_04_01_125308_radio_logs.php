<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RadioLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radio_logs', function (Blueprint $table) {
            $table->bigInteger('djid');
            $table->string('dj');
            $table->string('song');
            $table->bigInteger('listeners');
            $table->integer('time');
            $table->integer('day');
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
