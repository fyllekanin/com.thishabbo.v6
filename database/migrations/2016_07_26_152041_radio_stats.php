<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RadioStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radio_stats', function (Blueprint $table) {
            $table->string('dj');
            $table->string('song');
            $table->string('album_art');
            $table->bigInteger('listeners');
            $table->bigInteger('djid');
            $table->string('next_on_air');
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
        Schema::drop('radio_stats');
    }
}
