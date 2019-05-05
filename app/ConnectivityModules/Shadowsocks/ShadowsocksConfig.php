<?php

namespace App\ConnectivityModules\Shadowsocks;

use Illuminate\Database\Eloquent\Model;

class ShadowsocksConfig extends Model
{
    //
	protected $table = 'shadowsocks_info_configs';

	protected $fillable = ['node_id','status', 'override', 'port_start', 'port_end', 'encryption'];

	public static function getNodeConnectivityConfig($nodeID){
		return self::where('node_id', $nodeID)->first();
	}

	public static function updateNodeConnectivityConfig($nodeID, $data){
		return self::where('node_id', $nodeID)->update([
			'status' => $data['status'],
			'override' => $data['override'],
			'port_start' => $data['port_start'],
			'port_end' => $data['port_end'],
			'encryption' => implode(',', $data['encryption'])
		]);
	}

	public static function createNodeConnectivityConfig($nodeID){
		$config = self::getNodeConnectivityConfig(0);
		return self::create([
			'node_id' => $nodeID,
			'status' => !is_null($config['status']) ? $config['status'] : 0,
			'override' => !is_null($config['override']) ? $config['override'] : 0,
			'port_start' => !is_null($config['port_start']) ? $config['port_start'] : 100000,
			'port_end' => !is_null($config['port_end']) ? $config['port_end'] : 20000,
			'encryption' => !is_null($config['encryption']) ? $config['encryption'] : 'aes-128-gcm,aes-192-gcm,aes-256-gcm,aes-128-cfb,aes-192-cfb,'.
				'aes-256-cfb,aes-128-ctr,aes-192-ctr,aes-256-ctr,camellia-128-cfb,camellia-192-cfb,camellia-256-cfb,'.
				'bf-cfb,chacha20-ietf-poly1305,salsa20,chacha20,chacha20-ietf,rc4-md5',
		]);
	}
}
