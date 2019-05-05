<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{

	protected $fillable = ['name', 'address', 'real_ip', 'key', 'controller_port', 'area', 'description', 'rate', 'traffic',
		'speed_limit', 'allow_relay', 'hidden'];

	public static function getNodeInfoByNid($request){
		return self::where('id', $request)->first();
	}

	public static function checkIfNodeAllowRelay($request){
		return self::where('id', $request)->value('allow_relay');
	}

	public static function checkIfUserHasAccess($node, $user_id){
		foreach (array_column(UserGroup::getUserGroup($user_id, null)->toArray(), 'group_id') as $group_id)
			if(in_array($node, array_column(NodeGroup::getNodeInGroup($group_id)->toArray(),'node_id')))
				return true;
		return false;
	}

	public static function getAllNodeWithKeyword($request){
		return self::where('name', 'like', '%' . $request .'%')->
		orWhere('address', 'like', '%' . $request .'%')->
		orWhere('real_ip', 'like', '%' . $request .'%')->
		orWhere('description', 'like', '%' . $request .'%')->
		paginate(10);
	}

	public static function updateNodeBasicInfo($id, $name, $address, $realIP, $key, $controllerPort, $area,
	                                           $description, $rate, $speedLimit, $allowRelay, $hidden){
		//TODO: Optimize SQL query
		if(self::where('id', $id)->doesntExist())
			return 0;
		self::where('id', $id)->update([
			'name'=> $name,
			'address'=> $address,
			'real_ip'=> $realIP,
			'area' => $area,
			'key' => $key,
			'controller_port' => $controllerPort,
			'description'=> $description,
			'rate' => $rate,
			'speed_limit'=> $speedLimit,
			'allow_relay'=> $allowRelay,
			'hidden'=> $hidden
		]);
		return 1;
	}

	public static function createNodeBasicInfo($name, $address, $real_ip, $area, $description,
	                                           $rate, $speed_limit, $allow_relay, $hidden){
		return self::create([
			'name'=> $name,
			'address'=> $address,
			'real_ip'=> $real_ip,
			'key' => Common::generateRandomString(16),
			'controller_port' => 666,
			'area' => $area,
			'description'=> $description,
			'rate' => $rate,
			'speed_limit'=> $speed_limit,
			'allow_relay'=> $allow_relay,
			'hidden'=> $hidden
		]);
	}
}
