<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArticleComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_comments', function (Blueprint $table) {
            $table->bigIncrements('commentid');
            $table->bigInteger('parentid');
            $table->bigInteger('userid');
            $table->bigInteger('articleid');
            $table->text('content');
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
