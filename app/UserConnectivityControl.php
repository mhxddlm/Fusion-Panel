<?php

namespace App;

use App\ConnectivityModules\Shadowsocks\ShadowsocksUser;
use App\ConnectivityModules\ShadowsocksR\ShadowsocksRUser;
use App\ConnectivityModules\Vmess\VmessUser;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class UserConnectivityControl extends Model
{
	static protected $modules = [
		'shadowsocks' => ShadowsocksUser::class,
		'shadowsocksr' => ShadowsocksRUser::class,
		'vmess' => VmessUser::class
	];

	public static function getUserConnectivityInfo($moduleName, $userID, $nodeID){
		return self::$modules[$moduleName]::getUserConnectivityInfo($userID, $nodeID);
	}

	public static function updateUserConnectivityInfo($moduleName, $userID, $nodeID, $info){
		return self::$modules[$moduleName]::updateUserConnectivityInfo($userID, $nodeID, $info);
	}

	public static function createUserConnectivityInfo($moduleName, $userID, $nodeID){
		return self::$modules[$moduleName]::createUserConnectivityInfo($userID, $nodeID);
	}

	public static function UpdateUsersModuleJson($moduleName, $nodeID, $userIDsAdd = [], $userIDsDel = []){
		$data = self::$modules[$moduleName]::generateModuleJson($nodeID, $userIDsAdd, $userIDsDel);

		return FusionBackend::send("UpdateUsersConnectivityInfo", "update User in " .$moduleName, $nodeID, $data);
	}
}
