<?php

namespace App\ConnectivityModules\ShadowsocksR;

use Illuminate\Database\Eloquent\Model;

class ShadowsocksRConfig extends Model
{
    //
	protected $table = 'shadowsocksr_info_configs';

	protected $fillable = ['node_id', 'status', 'override', 'port_start', 'port_end', 'method', 'protocol', 'obfs'];

	public static function getNodeConnectivityConfig($nodeID){
		return self::where('node_id', $nodeID)->first();
	}

	public static function updateNodeConnectivityConfig($nodeID, $data){
		return self::where('node_id', $nodeID)->update([
			'status' => $data['status'],
			'override' => $data['override'],
			'port_start' => $data['port_start'],
			'port_end' => $data['port_end'],
			'method' => implode(',', $data['method']),
			'protocol' => implode(',', $data['protocol']),
			'obfs' => implode(',', $data['obfs'])
		]);
	}

	public static function createNodeConnectivityConfig($nodeID){
		$config = self::getNodeConnectivityConfig(0);
		return self::create([
			'node_id' => $nodeID,
			'status' => !is_null($config['status']) ? $config['status'] : 0,
			'override' => !is_null($config['override']) ? $config['override'] : 0,
			'port_start' => !is_null($config['port_start']) ? $config['port_start'] : 200001,
			'port_end' => !is_null($config['port_end']) ? $config['port_end'] : 30000,
			'method' => !is_null($config['method']) ? $config['method'] : 'none,aes-128-ctr,aes-192-ctr,aes-256-ctr,'.
				'aes-128-cfb,aes-192-cfb,aes-256-cfb,rc4,rc4-md5,rc4-md5-6,aes-128-cfb8,aes-192-cfb8,'.
				'aes-256-cfb8,salsa20,chacha20,xsalsa20,xchacha20,chacha20-ietf',
			'protocol' =>!is_null($config['protocol']) ? $config['protocol'] : 'origin,verify_deflate,auth_sha1_v4,'.
				'auth_aes128_md5,auth_aes128_sha1,auth_chain_a,auth_chain_b,auth_chain_c,auth_chain_d,'.
				'auth_chain_e,auth_chain_f,auth_akarin_rand,auth_akarin_spec_a',
			'obfs' => !is_null($config['obfs']) ? $config['obfs'] : 'plain,http_simple,http_post,random_head,tls1.2_ticket_auth,tls1.2_ticket_fastauth'
		]);
	}
}
