<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
	        $table->integer('order')->default('10')->unsigned();
	        $table->bigInteger('speed_limit')->unsigned()->nullable();
	        $table->string('config')->nullable();
            $table->boolean('hidden')->default(false);
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
        Schema::dropIfExists('group_infos');
    }
}
