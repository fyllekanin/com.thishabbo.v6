<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_log', function (Blueprint $table) {
            $table->bigIncrements('logid');
            $table->bigInteger('userid');
            $table->string('description');
            /*
                1 = usergroup
                2 = subscription (packages)
                3 = user
                4 = bbcode
                5 = moderation forum
                6 = maintenance
                7 = automated thread
                8 = forum
                9 = others
                10 = subscriptions (user)
                11 = badges to user
                12 = manage badges
                13 = prefix
                14 = name icons
                15 = voucher codes
                16 = name effect
                17 = theme
            */
            $table->bigInteger('content');
            $table->bigInteger('contentid');
            $table->string('extra_info')->default("");
            $table->bigInteger('affected_userid');
            $table->string('ip');
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
