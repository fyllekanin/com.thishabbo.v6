<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->bigIncrements('applicationid');
            $table->bigInteger('jobid');
            $table->bigInteger('userid');
            $table->string('skype');
            $table->string('region');
            $table->text('why');
            $table->text('experience');
            $table->text('bring');
            $table->text('previous');
            $table->bigInteger('open');
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
