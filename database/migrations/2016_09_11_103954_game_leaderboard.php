<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GameLeaderboard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_leaderboard', function (Blueprint $table) {
            $table->bigIncrements('scoreid');
            $table->bigInteger('userid');
            $table->bigInteger('score');
            $table->bigInteger('game');
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
