<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Furnis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('furnis', function (Blueprint $table) {
            $table->bigIncrements('furniid');
            $table->bigInteger('id');
            $table->string('classname');
            $table->bigInteger('revision');
            $table->string('name');
            $table->string('description');
            $table->integer('bc');
            $table->integer('canstandon');
            $table->integer('cansiton');
            $table->integer('canlayon');
            $table->integer('buyout');
            $table->string('furniline');
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
