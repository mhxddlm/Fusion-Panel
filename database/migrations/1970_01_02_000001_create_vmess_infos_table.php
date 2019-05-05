<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVmessInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vmess_info_configs', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('node_id')->unsigned();
	        $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
	        $table->tinyInteger('status')->default(1);
	        $table->tinyInteger('override')->default(0);
	        $table->integer('port')->unsigned();
	        $table->enum('encryption', ['auto', 'aes-128-cfb', 'aes-128-gcm', 'chacha20-ietf', 'none']);
            $table->timestamps();
        });
	    Schema::create('vmess_info_users', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('user_id')->unsigned();
		    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		    $table->integer('node_id')->unsigned();
		    $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
		    $table->tinyInteger('status')->default(1);
		    $table->tinyInteger('override')->default(0);
		    $table->uuid('uuid');
		    $table->integer('port')->unsigned();
		    $table->enum('encryption', ['auto', 'aes-128-cfb', 'aes-128-gcm', 'chacha20-ietf', 'none']);
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
        Schema::dropIfExists('vmess_info_configs');
	    Schema::dropIfExists('vmess_info_users');
    }
}
