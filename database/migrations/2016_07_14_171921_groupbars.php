<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Groupbars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('groupbars', function (Blueprint $table) {
          $table->bigInteger('usergroupid');
          $table->text('html');
          $table->text('css');
          $table->text('lastedited');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('groupbars');
    }
}
