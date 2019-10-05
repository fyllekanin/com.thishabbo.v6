<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mod_log', function (Blueprint $table) {
            $table->bigIncrements('logid');
            $table->bigInteger('userid');
            $table->string('description');
            /*
                1 = thread
                2 = post
                3 = article_comment
                4 = visitor message
                5 = user
                6 = creation
                7 = article_flagged
            */
            $table->bigInteger('content');
            $table->bigInteger('contentid');
            $table->string('extra_info')->default("");
            $table->bigInteger('affected_userid');
            $table->string('ip');
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
