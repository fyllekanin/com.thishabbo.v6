<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HabboBadges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habbo_badges', function (Blueprint $table) {
            $table->bigIncrements('badgeid');
            $table->string('badge_name');
            $table->string('badge_desc');
            $table->bigInteger('dateline');
            $table->text('subscribed_userids');
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
