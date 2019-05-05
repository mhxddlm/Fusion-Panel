<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('group_id')->unsigned();
	        $table->foreign('group_id')->references('id')->on('group_infos')->onDelete('cascade');
	        $table->bigInteger('traffic_up')->default(0)->unsigned();
	        $table->bigInteger('traffic_down')->default(0)->unsigned();
	        $table->bigInteger('traffic_limit')->unsigned()->nullable();
	        $table->dateTime('expire_time')->nullable();
	        $table->tinyInteger('type')->default(0)->unsigned();
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
        Schema::dropIfExists('user_groups');
    }
}
