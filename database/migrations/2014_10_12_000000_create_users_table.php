<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* LIKECOUNT - POST LIKES */
        /* LIKES - RADIO LIKES */
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('userid');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('usergroups');
            $table->bigInteger('displaygroup');
            $table->bigInteger('postcount')->default(0);
            $table->bigInteger('threadcount')->default(0);
            $table->bigInteger('likecount')->default(0);
            $table->bigInteger('commentcount')->default(0);
            $table->string('lastactivity');
            $table->string('lastip');
            $table->string('lastlogin');
            $table->string('loginip');
            $table->string('joindate');
            $table->bigInteger('country');
            $table->bigInteger('birthday')->default(0);
            $table->bigInteger('timezone');
            $table->string('lastavataredit');
            $table->bigInteger('postbit')->default(0);
            $table->string('hidebars')->default("");
            $table->text('signature')->default("");
            $table->string('habbo')->default("");
            $table->bigInteger('habbo_verified')->default(0);
            $table->string('bio')->default("");
            $table->bigInteger('likes')->default(0);
            $table->bigInteger('extras')->default(7);
            $table->bigInteger('credits')->default(0);
            $table->bigInteger('referdby')->default(0);
            $table->bigInteger('post_avatar')->default(1);
            $table->string('homePage')->default('');
            $table->string('postbit_badges')->default("");
            /* 0 = custom */
            $table->bigInteger('profile_header')->default(1);
            $table->bigInteger('name_icon')->default(0);
            $table->integer('name_icon_side')->default(0);
            $table->bigInteger('name_effect')->default(0);
            /* 0 = usergroup color */
            $table->bigInteger('username_option')->default(0);
            $table->string('username_color')->default("");
            $table->text('userbardata')->default("");
            $table->bigInteger('auto_subscribe')->default(1);
            $table->string('twitter')->default("");
            $table->bigInteger('amount_badges')->default(0);
            $table->string('theme')->default(0);
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
