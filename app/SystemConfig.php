<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
	/**
	 * Get the config using config code
	 * @param $code String
	 * @return mixed
	 */
    public static function getConfigValue($code){
    	return self::where('code', $code)->value('value');
    }

	/**
	 * Update certain config's code and value
	 * -----
	 * @param $id Integer
	 * @param $code String
	 * @param $value String
	 * @return int
	 * 0: config DNE
	 * 1: success
	 */
    public static function updateConfigValue($id, $code, $value){
    	if(self::where('id', $id)->doesntExist())
    		return 0;
    	self::where('id', $id)->update(['code' => $code, 'value' => $value]);
    	return 1;
    }
}
