<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShadowsocksInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shadowsocks_info_configs', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('node_id')->unsigned();
	        $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
	        $table->tinyInteger('status')->default(1);
	        $table->tinyInteger('override')->default(0);
	        $table->integer('port_start')->unsigned();
	        $table->integer('port_end')->unsigned();

	        $table->timestamps();
        });
	    DB::statement('ALTER TABLE shadowsocks_info_configs ADD encryption SET(\'aes-128-gcm\', \'aes-192-gcm\',
	        \'aes-256-gcm\', \'aes-128-cfb\', \'aes-192-cfb\', \'aes-256-cfb\', \'aes-128-ctr\', \'aes-192-ctr\',
	        \'aes-256-ctr\', \'camellia-128-cfb\', \'camellia-192-cfb\', \'camellia-256-cfb\', \'bf-cfb\',
	        \'chacha20-ietf-poly1305\', \'salsa20\', \'chacha20\', \'chacha20-ietf\', \'rc4-md5\') AFTER port_end');

	    Schema::create('shadowsocks_info_users', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('user_id')->unsigned();
		    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		    $table->integer('node_id')->unsigned();
		    $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
		    $table->tinyInteger('status')->default(1);
		    $table->tinyInteger('override')->default(0);
		    $table->integer('port');
		    $table->text('password');
		    $table->enum('encryption', ['aes-128-gcm', 'aes-192-gcm', 'aes-256-gcm', 'aes-128-cfb',
			    'aes-192-cfb', 'aes-256-cfb', 'aes-128-ctr', 'aes-192-ctr', 'aes-256-ctr', 'camellia-128-cfb',
			    'camellia-192-cfb', 'camellia-256-cfb', 'bf-cfb', 'chacha20-ietf-poly1305', 'salsa20',
			    'chacha20', 'chacha20-ietf', 'rc4-md5']);

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
        Schema::dropIfExists('shadowsocks_info_configs');
	    Schema::dropIfExists('shadowsocks_info_users');
    }
}
