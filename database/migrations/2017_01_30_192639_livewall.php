<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Livewall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livewall', function (Blueprint $table) {
            $table->bigIncrements('itemid');
            $table->bigInteger('userid');
            $table->bigInteger('forum');
            $table->bigInteger('forumid');
            $table->bigInteger('item_id');
            $table->bigInteger('item_type'); //thread = 1, post = 2, creation = 3, article = 4, profile = 5
            $table->string('message'); //liked post, created thread, posted, liked creation, commented on creation, followed user etc
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
