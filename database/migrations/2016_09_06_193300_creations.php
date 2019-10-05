<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Creations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creations', function (Blueprint $table) {
            $table->bigIncrements('creationid');
            $table->string('name');
            $table->string('tags');
            $table->bigInteger('userid');
            $table->bigInteger('dateline');
            $table->bigInteger('likes');
            $table->bigInteger('comments');
            $table->bigInteger('approved');
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
