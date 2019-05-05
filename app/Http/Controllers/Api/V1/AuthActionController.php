<?php

namespace App\Http\Controllers\Api\V1;

use App\InviteCode;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthActionController extends Controller
{
	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function getCodeInfo(Request $request){
		$a['code'] = InviteCode::checkIfUsable($request['code']);
		if($a['code'] == 1)
			$a['name'] = User::findbyUID(InviteCode::getInvitorID($request['code']))['name'];
		return $a;
	}

	public function checkEmailUsername(Request $request){
		$a["email"] = 1;
		$a["name"] = 1;
		if(User::where('email', $request['email'])->exists())
			$a["email"] = 0;
		if(User::where('name', $request['name'])->exists())
			$a["name"] = 0;
		return $a;
	}

	public function logout(){
		Auth::logout();
		var_dump(strtolower(env('APP_NAME')));
		unset($_COOKIE[strtolower(env('APP_NAME')) . '_session']);
		setcookie(strtolower(env('APP_NAME')) . '_session', null, -1, '/');
		unset($_COOKIE['XSRF-TOKEN']);
		setcookie('XSRF-TOKEN', null, -1, '/');
	}
}
