<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Shoutbox extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoutbox', function (Blueprint $table) {
            $table->bigIncrements('messageid');
            $table->bigInteger('shoutboxid');
            $table->bigInteger('userid');
            $table->text('message');
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
