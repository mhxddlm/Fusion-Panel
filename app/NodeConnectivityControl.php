<?php

namespace App;

use App\ConnectivityModules\Shadowsocks\ShadowsocksConfig;
use App\ConnectivityModules\ShadowsocksR\ShadowsocksRConfig;
use App\ConnectivityModules\Vmess\VmessConfig;
use Illuminate\Database\Eloquent\Model;

class NodeConnectivityControl extends Model
{
	static protected $modules = [
		'shadowsocks' => ShadowsocksConfig::class,
		'shadowsocksr' => ShadowsocksRConfig::class,
		'vmess' => VmessConfig::class
	];

	public static function getNodeConnectivityConfig($moduleName, $nodeID){
		return self::$modules[$moduleName]::getNodeConnectivityConfig($nodeID);
	}

	public static function updateNodeConnectivityConfig($moduleName, $nodeID, $data){
		return self::$modules[$moduleName]::updateNodeConnectivityConfig($nodeID, $data);
	}

	public static function createNodeConnectivityConfig($moduleName, $nodeID){
		return self::$modules[$moduleName]::createNodeConnectivityConfig($nodeID);
	}
}
