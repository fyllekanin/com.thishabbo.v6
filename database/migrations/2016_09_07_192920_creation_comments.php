<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreationComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creation_comments', function (Blueprint $table) {
            $table->bigIncrements('commentid');
            $table->bigInteger('userid');
            $table->bigInteger('creationid');
            $table->string('content');
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
