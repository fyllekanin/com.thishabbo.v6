<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FlagedArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flaged_articles', function (Blueprint $table) {
            $table->bigIncrements('flagid');
            $table->bigInteger('userid');
            $table->bigInteger('articleid');
            $table->text('reason');
            $table->integer('type');
            $table->integer('handled')->default(0);
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
