<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StaffList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_list', function (Blueprint $table) {
            $table->bigIncrements('staffid');
            $table->bigInteger('usergroupid');
            $table->string('color');
            $table->bigInteger('displayorder');
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
