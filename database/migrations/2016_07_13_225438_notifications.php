<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Notifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('notifications', function (Blueprint $table) {
          $table->bigIncrements('notificationid');
          $table->bigInteger('postuserid');
          $table->bigInteger('reciveuserid');
          $table->bigInteger('content');
          /*
          * 1 = mention
          * 2 = quote
          * 3 = like
          * 4 = posted_in_your_thread
          * 5 = private_message
          * 6 = visitor_message
          * 7 = award/badge
          * 8 = quest guide
          * 9 = followed you
          * 10 = refferd you
          * 11 = mention you in article
          * 12 = mention you in creation
          * 13 = points gift
          * 14 = reply to comment
          */
          $table->bigInteger('contentid');
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
        Schema::drop('notifications');
    }
}
