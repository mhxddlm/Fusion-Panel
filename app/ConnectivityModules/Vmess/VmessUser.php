<?php

namespace App\ConnectivityModules\Vmess;

use App\ConnectivityModules\ConnectivityUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VmessUser extends Model implements ConnectivityUser
{
    //
	protected $table = 'vmess_info_users';

	protected $fillable = [
		'user_id', 'node_id', 'status', 'override', 'uuid', 'port', 'encryption'
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
			'encryption' => $data['encryption']
		]);
	}

	public static function createUserConnectivityInfo($userID, $nodeID){
		$config = VmessConfig::getNodeConnectivityConfig($nodeID);
		return self::create([
			'user_id' => $userID,
			'node_id' => $nodeID,
			'status' => $config['status'],
			'override' => $config['override'],
			'uuid' => (string) Str::uuid(),
			'port' => $config['port'],
			'encryption' => $config['encryption']
		]);
	}
}
