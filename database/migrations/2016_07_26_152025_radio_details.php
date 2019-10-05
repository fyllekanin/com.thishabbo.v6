<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RadioDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radio_details', function (Blueprint $table) {
            $table->bigIncrements('infoid');
            $table->string('ip');
            $table->bigInteger('port');
            $table->string('password');
            $table->string('admin_password');
            $table->bigInteger('userid');
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
        Schema::drop('radio_details');
    }
}
