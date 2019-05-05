<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string("address");
	        $table->string("real_ip");
	        $table->integer("controller_port");
	        $table->string("key");
            $table->char('area', 2);
            $table->string('description');
            $table->double('rate')->default(1.00);
            $table->bigInteger('traffic')->default(0)->unsigned();
            $table->bigInteger('speed_limit')->unsigned()->nullable(); //Override all limits
	        $table->boolean('allow_relay')->default(true);
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
        Schema::dropIfExists('nodes');
    }
}
