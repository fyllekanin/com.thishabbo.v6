<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Thread extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->bigIncrements('threadid');
            $table->string('title');
            $table->bigInteger('forumid');
            $table->bigInteger('open')->default(1);
            $table->bigInteger('visible')->default(1);
            $table->bigInteger('replys')->default(0);
            $table->bigInteger('postuserid');
            $table->bigInteger('prefixid')->default(0);
            $table->bigInteger('dateline');
            $table->bigInteger('firstpostid');
            $table->bigInteger('lastpost');
            $table->bigInteger('got_poll')->default(0);
            $table->bigInteger('lastpostid');
            $table->bigInteger('sticky')->default(0);
            $table->bigInteger('views')->default(0);
            $table->bigInteger('force_read')->default(0);
            $table->bigInteger('lastedited');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('threads');
    }
}
