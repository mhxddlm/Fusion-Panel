<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShadowsocksrInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shadowsocksr_info_configs', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('node_id')->unsigned();
	        $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
	        $table->tinyInteger('status')->default(1);
	        $table->tinyInteger('override')->default(0);
	        $table->integer('port_start')->unsigned();
	        $table->integer('port_end')->unsigned();

	        $table->timestamps();
        });
	    DB::statement('ALTER TABLE shadowsocksr_info_configs ADD method SET(\'none\', \'aes-128-ctr\',
	        \'aes-192-ctr\', \'aes-256-ctr\', \'aes-128-cfb\', \'aes-192-cfb\', \'aes-256-cfb\', \'rc4\',
	        \'rc4-md5\', \'rc4-md5-6\', \'aes-128-cfb8\', \'aes-192-cfb8\', \'aes-256-cfb8\', \'salsa20\',
	        \'chacha20\', \'xsalsa20\', \'xchacha20\', \'chacha20-ietf\') AFTER port_end');
	    DB::statement('ALTER TABLE shadowsocksr_info_configs ADD protocol SET(\'origin\', \'verify_deflate\',
	        \'auth_sha1_v4\', \'auth_aes128_md5\', \'auth_aes128_sha1\', \'auth_chain_a\', \'auth_chain_b\',
	        \'auth_chain_c\', \'auth_chain_d\', \'auth_chain_e\', \'auth_chain_f\', \'auth_akarin_rand\',
	        \'auth_akarin_spec_a\') AFTER method');
	    DB::statement('ALTER TABLE shadowsocksr_info_configs ADD obfs SET(\'plain\', \'http_simple\',
	        \'http_post\', \'random_head\', \'tls1.2_ticket_auth\', \'tls1.2_ticket_fastauth\') AFTER protocol');

	    Schema::create('shadowsocksr_info_users', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('user_id')->unsigned();
		    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		    $table->integer('node_id')->unsigned();
		    $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
		    $table->tinyInteger('status')->default(1);
		    $table->tinyInteger('override')->default(0);
		    $table->integer('port');
		    $table->text('password');
		    $table->enum('method', ['none', 'aes-128-ctr', 'aes-192-ctr', 'aes-256-ctr', 'aes-128-cfb',
			    'aes-192-cfb', 'aes-256-cfb', 'rc4', 'rc4-md5', 'rc4-md5-6', 'aes-128-cfb8', 'aes-192-cfb8',
			    'aes-256-cfb8', 'salsa20', 'chacha20', 'xsalsa20', 'xchacha20', 'chacha20-ietf']);
		    $table->enum('protocol', ['origin', 'verify_deflate', 'auth_sha1_v4', 'auth_aes128_md5',
			    'auth_aes128_sha1', 'auth_chain_a', 'auth_chain_b', 'auth_chain_c', 'auth_chain_d', 'auth_chain_e',
			    'auth_chain_f', 'auth_akarin_rand', 'auth_akarin_spec_a']);
		    $table->enum('obfs', ['plain', 'http_simple', 'http_post', 'random_head', 'tls1.2_ticket_auth',
			    'tls1.2_ticket_fastauth']);
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
	    Schema::dropIfExists('shadowsocksr_info_configs');
        Schema::dropIfExists('shadowsocksr_info_users');
    }
}
