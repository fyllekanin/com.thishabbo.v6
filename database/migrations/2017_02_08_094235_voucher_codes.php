<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VoucherCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_codes', function (Blueprint $table) {
            $table->bigIncrements('voucherid');
            $table->bigInteger('userid');
            $table->bigInteger('worth');
            $table->string('code');
            $table->bigInteger('active')->default(1);
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
