<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeStatusInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_status_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned();
	        $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
	        $table->double('cpu');
	        $table->double('memory');
	        $table->double('load');
	        $table->bigInteger('upload')->unsigned();
	        $table->bigInteger('download')->unsigned();
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
        Schema::dropIfExists('node_status_infos');
    }
}
