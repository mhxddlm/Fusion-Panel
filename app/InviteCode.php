<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
	protected $fillable = [
		'code', 'money_include', 'gen_by', 'limit', 'enable'
	];
	/**
	 * Return all compatible invite codes
	 * -----
	 * @param $userID UserID
	 * @return mixed
	 */
	public static function getInviteCode($userID = null){
		return self::when(!empty($userID), function($query) use ($userID){
			return $query->where('gen_by', $userID);
		})->get();
	}

	public static function getInvitorID($code = null){
		return self::where('code', $code)->value('gen_by');
	}

	/**
	 * check if invite code is usable
	 * -----
	 * @param $code invite code
	 * @return int
	 * 0: code DNE
	 * -1: code no longer available
	 * -2: invitor account deactivated
	 * 1: code usable
	 */
	public static function checkIfUsable($code){
		$codeInfo = self::where('code', $code)->first();
		if(!$codeInfo)
			return 0;
		if($codeInfo['used_count'] >= $codeInfo['limit'])
			return -1;
		if(User::findbyUID($codeInfo['gen_by'])['status'] == 0)
			return -2;
		return 1;
	}

	/**
	 * Add new invite code to db
	 * -----
	 * @param $code
	 * @param $money
	 * @param $limit
	 * @param $enable
	 * @param $genBy
	 * @return int
	 * -1: code already exists
	 * 1: success
	 */
	public static function newInviteCode($code, $money, $limit, $enable, $genBy){
		if(self::where('code', $code)->exists())
			return -1;
		self::create([
			'code' => $code,
			'money_include' => $money,
			'limit' => $limit,
			'enable' => $enable,
			'gen_by' => $genBy]);
		return 1;
	}

	/**
	 * Update a existing invite code
	 * -----
	 * @param $id
	 * @param $code
	 * @param $money
	 * @param $limit
	 * @param $enable
	 * @return int
	 * 0: code DNE
	 * -1: code name exists, pls change
	 * 1: success
	 */
	public static function updateInviteCode($id, $code, $money, $limit, $enable){
		if(self::where('id', $id)->doesntExist())
			return 0;
		if(self::where([['id', '<>', $id], ['code', $code]])->exists())
			return -1;
		self::where('id', $id)->update([
			'code' => $code,
			'money_include' => $money,
			'limit' => $limit,
			'enable' => $enable
		]);
	}

	public static function inviteCodeUsed($code){
		self::where('code', $code)->increment('used_count');
	}
}
