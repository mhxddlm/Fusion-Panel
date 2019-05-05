<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupInfo extends Model
{
	protected $fillable = ['name', 'description', 'order', 'speed_limit', 'config', 'hidden'];

	/**
	 * @param $showHidden
	 * @return mixed
	 */
	public static function getAllGroups($showHidden){
		return $showHidden ? self::get() : self::where('hidden', 0)->get();
	}

	public static function getGroupByID($groupID){
		return self::where('id', $groupID)->first();
	}

	public static function newGroup(){

	}

	public static function updateGroupInfo($id, $name, $description, $order, $speedLimit, $config, $hidden){
		if(self::where('id', $id)->doesntExist())
			return 0;
		return self::where('id', $id)->update([
			'name' => $name,
			'description' => $description,
			'order' => $order,
			'speed_limit' => $speedLimit,
			'config' => $config,
			'hidden' => $hidden
		]);
	}

	public static function createGroupInfo($name, $description, $order, $speedLimit, $hidden){
		return self::create([
			'name' => $name,
			'description' => $description,
			'order' => $order,
			'speed_limit' => $speedLimit,
			'hidden' => $hidden
		]);
	}
}
