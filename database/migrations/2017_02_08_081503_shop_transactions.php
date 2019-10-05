<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_transactions', function (Blueprint $table) {
            $table->bigIncrements('transactionid');
            $table->bigInteger('userid');
            $table->bigInteger('action'); // 1 = bought , 2 = redeemed, 3 = gift
            $table->bigInteger('item'); //1 = name icon , 2 = voucher code, 3 = name effect, 4 = subscription, 5 = amount points, 6 = themes
            $table->bigInteger('itemid'); // item 5 = userid
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
