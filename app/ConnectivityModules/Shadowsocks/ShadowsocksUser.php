<?php

namespace App\ConnectivityModules\Shadowsocks;

use App\Common;
use Illuminate\Database\Eloquent\Model;

class ShadowsocksUser extends Model
{
    //
	protected $table = 'shadowsocks_info_users';

	protected $fillable = [
		'user_id', 'node_id', 'status', 'override', 'port', 'password', 'encryption'
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
			'encryption' => $data['encryption']
		]);
	}

	public static function createUserConnectivityInfo($userID, $nodeID){
		$config = ShadowsocksConfig::getNodeConnectivityConfig($nodeID);
		return self::create([
			'user_id' => $userID,
			'node_id' => $nodeID,
			'status' => $config['status'],
			'override' => $config['override'],
			'port' => self::generateLegalPort($config),
			'password' => Common::generateRandomString(),
			'encryption' => explode(',', $config['encryption'])[0]
		]);
	}

	public static function generateUserConnectivityUrl($userID, $nodeID){

	}

	public static function generateModuleJson($nodeID, $userIDAdd = [], $userIDDel = []){
		$data["ShadowsocksManager"] = array_merge(
			self::getUsersAddProfileInModuleJson($nodeID, $userIDAdd),
			self::getUsersDeleteProfileInModuleJson($nodeID, $userIDDel));

		return (object) $data;
	}

	private static function generateLegalPort($config){
		//should use dJBX33A
		$portsExisted = self::where('node_id', $config['node_id'])->get(['port'])->toArray();
		do{
			$port = rand($config['port_start'], $config['port_end']);
		}
		while(in_array($port, $portsExisted));
		return $port;
	}


	private static function getUsersAddProfileInModuleJson($nodeID, $userIDs = []){
		$dataAll = array();
		foreach($userIDs as $userID){
			$data['action'] = "add";
			$c = self::getUserConnectivityInfo($userID, $nodeID)->first();
			$t['server_port'] = (int)$c['port'];
			$t['password'] = $c['password'];
			$t['method'] = $c['encryption'];
			$data['conf'] = (object) $t;
			array_push($dataAll, (object) $data);
		}
		return $dataAll;
	}

	private static function getUsersDeleteProfileInModuleJson($nodeID, $userIDs = []){
		$dataAll = array();
		foreach($userIDs as $userID){
			$data['action'] = "delete";
			$c = self::getUserConnectivityInfo($userID, $nodeID)->first();
			$t['server_port'] = (int)$c['port'];
			$data['conf'] = (object) $t;
			array_push($dataAll, (object) $data);
		}
		return $dataAll;
	}
}
