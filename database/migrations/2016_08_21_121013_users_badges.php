<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersBadges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_badges', function (Blueprint $table) {
            $table->bigIncrements('userbadgeid');
            $table->bigInteger('userid');
            $table->bigInteger('badgeid');
            $table->bigInteger('selected')->default(0);
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
