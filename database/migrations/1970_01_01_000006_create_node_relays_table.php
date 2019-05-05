<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeRelaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_relays', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('user_id')->unsigned();
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	        $table->text('name');
	        $table->integer('relay_node')->unsigned();
	        $table->foreign('relay_node')->references('id')->on('nodes')->onDelete('cascade');
	        $table->integer('final_node')->unsigned();
	        $table->foreign('final_node')->references('id')->on('nodes')->onDelete('cascade');

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
        Schema::dropIfExists('node_relays');
    }
}
