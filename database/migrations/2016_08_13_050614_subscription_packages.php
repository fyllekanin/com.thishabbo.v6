<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->bigIncrements('packageid');
            $table->string('name');
            $table->text('description');
            $table->bigInteger('usergroupid')->default(0);
            $table->bigInteger('price');
            $table->string('userbar_text');
            $table->bigInteger('dateline');
            $table->bigInteger('lastedit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subscription_packages');
    }
}
