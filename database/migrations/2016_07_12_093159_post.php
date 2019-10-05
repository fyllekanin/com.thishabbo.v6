<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Post extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('postid');
            $table->bigInteger('threadid');
            $table->string('username');
            $table->bigInteger('userid');
            $table->bigInteger('dateline');
            $table->bigInteger('lastedit')->default(0);
            $table->text('content');
            $table->string('ipaddress');
            $table->bigInteger('visible')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
