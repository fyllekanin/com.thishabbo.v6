<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForgotPasswords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forgot_passwords', function (Blueprint $table) {
            $table->bigIncrements('codeid');
            $table->bigInteger('userid');
            $table->string('code');
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
