<?php

use Illuminate\Support\Facades\Schema;
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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('email')->unique();
	        $table->timestamp('email_verified_at')->nullable();
	        $table->string('phone')->unique()->nullable();
	        $table->timestamp('phone_verified_at')->nullable();
	        $table->boolean('status')->default(false);
            $table->string('password');
            $table->double('balance',8,2)->default(0.00);
            $table->bigInteger('speed_limit')->unsigned()->nullable(); //Kbps
            $table->integer('invited_by');
            $table->integer('invite_token_remain')->default(0);
	        $table->integer('promote_program')->default(0);
	        $table->double('promote_money',8,2)->default(0.00);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
