<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModerationForums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moderation_forums', function (Blueprint $table) {
            $table->bigIncrements('mfid');
            $table->bigInteger('forumid');
            $table->string('title');
            $table->bigInteger('prefixid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('moderation_forums');
    }
}
