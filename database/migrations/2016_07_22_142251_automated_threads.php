<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AutomatedThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automated_threads', function (Blueprint $table) {
            $table->bigIncrements('atid');
            $table->bigInteger('forumid');
            $table->bigInteger('postuserid');
            $table->string('title');
            $table->text('content');
            $table->bigInteger('dateline');
            $table->bigInteger('day');
            $table->bigInteger('hour');
            $table->bigInteger('minute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('automated_threads');
    }
}
