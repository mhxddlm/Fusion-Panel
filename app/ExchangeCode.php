<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangeCode extends Model
{
    protected $fillable = [
    	'code', 'money', 'used_count', 'limit', 'expire_at'
    ];

    public static function getAll(){
    	return self::select('id', 'code', 'money', 'used_count', 'limit', 'expire_at')->get();
    }

    public static function createExchangeCode($code, $money, $limit, $expireTime){
    	self::create([
    		'code' => $code,
		    'money' => $money,
		    'limit' => $limit,
		    'expire_at' => $expireTime
	    ]);
    	return 1;
    }

    public static function updateExchangeCode($id, $code, $money, $limit, $expireTime){
	    if(self::where('id', $id)->doesntExist())
		    return 0;
    	self::where('id', $id)->update([
		    'code' => $code,
		    'money' => $money,
		    'limit' => $limit,
		    'expire_at' => $expireTime
	    ]);
    	return 1;
    }
}
