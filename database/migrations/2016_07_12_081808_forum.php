<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Forum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->bigIncrements('forumid');
            $table->string('title');
            $table->string('description');
            $table->bigInteger('parentid')->default(-1);
            $table->bigInteger('options')->default(0);
            $table->bigInteger('displayorder')->default(0);
            $table->bigInteger('posts')->default(0);
            $table->bigInteger('threads')->default(0);
            $table->bigInteger('lastpost')->default(0);
            $table->bigInteger('lastpostid')->default(0);
            $table->bigInteger('lastposterid')->default(0);
            $table->bigInteger('lastthread')->default(0);
            $table->bigInteger('lastthreadid')->default(0);
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
        Schema::drop('forums');
    }
}
