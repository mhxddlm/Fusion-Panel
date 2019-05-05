<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NodeGroup extends Model
{
    //
	public static function getNodeInGroup($groupID){
		return self::where('group_id', $groupID)->get();
	}
}
