<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

	public $timestamps = false;
	protected $table = 'admins';
	protected $fillable = ['user_id', 'permission_id'];

	public static function getAdmins(){
		return self::join('permissions', 'admins.permission_id', '=', 'permissions.id')
			->select('user_id')->groupBy('user_id')->get();
	}

	/*public static function getAllPermission($withName = false){
		return self::join('permissions', 'admins.permission_id', '=', 'permissions.id')->
			select("admins.*", "permissions.*")->
			when($withName, function($query) use ($withName){
				return $query->leftJoin('users', 'admins.user_id', '=', 'users.id')->addSelect("users.name AS username");
			})->get();
	}*/

	public static function getAdminAllPermission($userID, $groupBy = 'method'){
		return self::where('user_id', $userID)
			->join('permissions', 'admins.permission_id', '=', 'permissions.id')->get()->groupBy($groupBy);
	}

	public static function checkIfAdminGranted($userID, $method, $name){
		return self::join('permissions', 'admins.permission_id', '=', 'permissions.id')->
		where([['user_id', $userID], ['method', $method], ['name', $name]])->exists();
	}

	public static function addPermission($userID, $permissions){
		$k = [];
		foreach($permissions as $t){
			array_push($k, array('user_id'=> $userID, 'permission_id'=> $t));
		}
		return self::insert($k);
	}

	public static function deletePermission($userID, $permissions){
		$k = [];
		foreach($permissions as $t){
			array_push($k, self::where([['user_id', $userID], ['permission_id', $t]])->pluck('id'));
		}
		return self::destroy($k);
	}
}
