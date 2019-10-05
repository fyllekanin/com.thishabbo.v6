<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DjLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dj_likes', function (Blueprint $table) {
            $table->bigIncrements('likeid');
            $table->bigInteger('djid');
            $table->bigInteger('userid');
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
