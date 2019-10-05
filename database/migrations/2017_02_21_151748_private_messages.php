<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrivateMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_messages', function (Blueprint $table) {
            $table->bigIncrements('pmid');
            $table->bigInteger('recive_userid');
            $table->bigInteger('post_userid');
            $table->text('content');
            $table->bigInteger('dateline');
            $table->bigInteger('read_at');
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
