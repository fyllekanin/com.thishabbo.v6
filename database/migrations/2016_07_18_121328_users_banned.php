<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersBanned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_banned', function (Blueprint $table) {
            $table->bigIncrements('banid');
            $table->bigInteger('userid');
            $table->bigInteger('adminid');
            $table->bigInteger('banned_at');
            $table->bigInteger('banned_until');
            $table->string('reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_banned');
    }
}
