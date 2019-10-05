<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Articles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('articleid');
            $table->string('title');
            $table->text('content');
            $table->bigInteger('userid');
            $table->bigInteger('dateline');
            /*
                0 = Quest Guide
                1 = News Article
                2 = Wired Guide
                3 = Tips & Tricks
            */
            $table->bigInteger('type');
            $table->string('badge_code');
            $table->bigInteger('available')->default(0);
            $table->string('room_link')->default("");
            $table->bigInteger('approved')->default(1);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
    }
}
