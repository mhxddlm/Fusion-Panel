<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->unsigned();
	        $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->integer('group_id')->unsigned();
	        $table->foreign('group_id')->references('id')->on('group_infos')->onDelete('cascade');
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
        Schema::dropIfExists('node_groups');
    }
}
