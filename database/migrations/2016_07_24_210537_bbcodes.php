<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bbcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbcodes', function (Blueprint $table) {
            $table->bigIncrements('bbcodeid');
            $table->string('name');
            $table->string('example');
            $table->text('pattern');
            $table->text('replace');
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
        Schema::drop('bbcodes');
    }
}
