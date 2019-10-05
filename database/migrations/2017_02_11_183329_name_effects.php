<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NameEffects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('name_effects', function (Blueprint $table) {
            $table->bigIncrements('effectid');
            $table->string('name');
            $table->text('description');
            $table->bigInteger('price');
            $table->bigInteger('limit');
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
