<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionSubs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_subs', function (Blueprint $table) {
            $table->bigIncrements('subid');
            $table->bigInteger('userid');
            $table->bigInteger('packageid');
            $table->bigInteger('start_date');
            $table->bigInteger('end_date');
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
