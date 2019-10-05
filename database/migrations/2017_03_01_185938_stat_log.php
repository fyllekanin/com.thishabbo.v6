<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats_log', function (Blueprint $table) {
            $table->bigIncrements('statid');
            $table->bigInteger('posts');
            $table->bigInteger('threads');
            $table->bigInteger('creations');
            $table->bigInteger('creation_comments');
            $table->bigInteger('articles');
            $table->bigInteger('article_comments');
            $table->bigInteger('visitor_messages');
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
