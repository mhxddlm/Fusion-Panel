<?php

namespace App\ConnectivityModules\ShadowsocksR;

use App\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShadowsocksRUser extends Model
{
    //
	protected $table = 'shadowsocksr_info_users';

	protected $fillable = [
		'user_id', 'node_id', 'status', 'override', 'port', 'password', 'method', 'protocol', 'obfs'
	];

	public static function getUserConnectivityInfo($userID = null, $nodeID = null){
		return self::when(!is_null($userID), function($query) use ($userID){
			return $query->where('user_id', $userID);
		})->when(!is_null($nodeID), function($query) use ($nodeID){
			return $query->where('node_id', $nodeID);
		})->get();
	}

	public static function updateUserConnectivityInfo($userID, $nodeID, $data){
		return self::where([['user_id', $userID], ['node_id', $nodeID]])->update([
			'status' => $data['status'],
			'password' => $data['password'],
			'method' => $data['method'],
			'protocol' => $data['protocol'],
			'obfs' => $data['obfs']
		]);
	}

	public static function createUserConnectivityInfo($userID, $nodeID){
		$config = ShadowsocksRConfig::getNodeConnectivityConfig($nodeID);
		return self::create([
			'user_id' => $userID,
			'node_id' => $nodeID,
			'status' => $config['status'],
			'override' => $config['override'],
			'port' => self::generateLegalPort($config),
			'password' => Common::generateRandomString(),
			'method' => explode(',', $config['method'])[0],
			'protocol' => explode(',', $config['protocol'])[0],
			'obfs' =>explode(',', $config['obfs'])[0]
		]);
	}

	private static function generateLegalPort($config){
		$portsExisted = self::where('node_id', $config['node_id'])->get(['port'])->toArray();
		do{
			$port = rand($config['port_start'], $config['port_end']);
		}
		while(in_array($port, $portsExisted));
		return $port;
	}
}
