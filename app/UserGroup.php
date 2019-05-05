<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
	protected $fillable = [
		'user_id', 'group_id', 'traffic_up', 'traffic_down', 'traffic_limit', 'expire_time', 'type'
	];

	/**
	 * Return user-group relation table with selected options.
	 * @param null $userID
	 * @param null $groupID
	 * @return Eloquent Model
	 */

    public static function getUserGroup($userID = null, $groupID = null){
    	return self::when(!empty($userID), function($query) use ($userID){
    		return $query->where('user_id', $userID);
	    })->when(!empty($groupID), function($query) use ($groupID){
	    	return $query->where('group_id', $groupID);
	    })->get();
    }

	/**
	 * add a group to user
	 * -----
	 * @param $userID
	 * @param $groupID
	 * @param $trafficLimit
	 * @param $expireTime
	 * @param $type
	 * @return int
	 * 0: this user has a same group with same deduction level.
	 * 1: success
	 */
    public static function addUserGroup($userID, $groupID, $trafficLimit, $expireTime, $type){
    	if(self::where([['user_id', $userID],['group_id', $groupID], ['type', $type]])->exists())
    		return 0;
    	self::create([
    		'user_id' => $userID,
		    'group_id' => $groupID,
		    'traffic_limit' => $trafficLimit,
		    'expire_time' => $expireTime,
		    'type' => $type
	    ]);
    	return 1;
    }

	/**
	 * update an existing group for this user
	 * @param $id
	 * @param $userID
	 * @param $groupID
	 * @param $trafficLimit
	 * @param $expireTime
	 * @param $type
	 * @return int
	 * 0: user_group id not found.
	 */
    public static function updateUserGroup($id, $userID, $groupID, $trafficLimit, $expireTime, $type){
	    if(self::where([['id', $id], ['user_id', $userID], ['group_id', $groupID]])->doesntExist())
	    	return 0;
	    self::where('id', $id)->update([
	    	'traffic_limit' => $trafficLimit,
		    'expire_time' => $expireTime,
		    'type' => $type
	    ]);
    }

    public static function deleteUserGroup($id){
    	return self::where('id', $id)->delete();
    }

    public static function getUsersInGroup($id){
    	return self::where('group_id', $id)->get();
    }

    public static function getGroupUsage($id){
    	$t['up'] = (int)self::where('group_id', $id)->sum('traffic_up');
	    $t['down'] = (int)self::where('group_id', $id)->sum('traffic_down');
	    return $t;
    }
}
