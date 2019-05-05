<?php

namespace App\ConnectivityModules\Vmess;

use Illuminate\Database\Eloquent\Model;

class VmessConfig extends Model
{
    //
	protected $table = 'vmess_info_configs';

	protected $fillable = ['node_id', 'status', 'override', 'port', 'encryption'];

	public static function getNodeConnectivityConfig($nodeID){
		return self::where('node_id', $nodeID)->first();
	}

	public static function updateNodeConnectivityConfig($nodeID, $data){
		return self::where('node_id', $nodeID)->update([
			'status' => $data['status'],
			'override' => $data['override'],
			'port' => $data['port'],
			'encryption' => $data['encryption']
		]);
	}

	public static function createNodeConnectivityConfig($nodeID){
		$config = self::getNodeConnectivityConfig(0);
		return self::create([
			'node_id' => $nodeID,
			'status' => !is_null($config['status']) ? $config['status'] : 0,
			'override' => !is_null($config['override']) ? $config['override'] : 0,
			'port' => !is_null($config['port']) ? $config['port'] : 2333,
			'encryption' => !is_null($config['encryption']) ? $config['encryption'] : 'auto',
		]);
	}
}
