<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\User;

class ApiLoginController extends Controller
{
    //
	public function loginHandler(){
		$http = new Client();
		$userInfo = $http->get(env('API_URL').'/api/v1/userInfo',
			[
				'headers' =>[
					'Authorization' => "Bearer " . session('api-token'),
				]
			]);
		$userInfo = json_decode((string) $userInfo->getBody(), true);
		$userInfoinBase = User::findbyEmail($userInfo['email']);

		if(is_null($userInfoinBase))
			return 0;
		else{
			return 1;
		}
		return -1;
	}

	public function getApiToken(){
		return session('api-token');
	}
}
