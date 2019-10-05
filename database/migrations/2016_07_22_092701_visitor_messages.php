<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VisitorMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_messages', function (Blueprint $table) {
            $table->bigIncrements('vmid');
            $table->bigInteger('postuserid');
            $table->bigInteger('reciveuserid');
            $table->text('message');
            $table->bigInteger('dateline');
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
        Schema::drop('visitor_messages');
    }
}
