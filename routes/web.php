<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PublicController@index')->name('public.index');

Route::group(['prefix' => '/auth'], function () {
	Route::get('Nyaccount', function () {
		if (!env('API_CLIENT_ID') || !env('API_URL') || !env('API_CLIENT_SECRET')) {
			return "Please fill API fields in env file";
		}
		$query = http_build_query([
			'client_id' => env('API_CLIENT_ID'),
			'response_type' => 'code',
			'scope' => '*',
		]);

		return redirect(env('THIRD_PARTY_LOGIN_URL').'/oauth/authorize?'.$query);
	});
	Route::get('callback', function () {
		$http = new GuzzleHttp\Client;

		$response = $http->post(env('API_URL').'/oauth/token', [
			'form_params' => [
				'grant_type' => 'authorization_code',
				'client_id' => env('API_CLIENT_ID'),
				'client_secret' => env('API_CLIENT_SECRET'),
				'code' => request()->code,
				'scope' => '*',
			],
		]);

		$apiResponse = json_decode((string) $response->getBody(), true);
		session(['api'=> $apiResponse]);
		session(['api-token'=> $apiResponse['access_token']]);
		return redirect('/auth/apiLogin');
	});
	//Route::get('apiLogin', 'Auth\ApiLoginController@loginHandler')->name('auth.apiLogin');
	//Route::get('apiRegister', 'Auth\ApiLoginController@loginHandler')->name('auth.apiRegister');
});

Auth::routes();

Route::group(['prefix' => '/auth', 'middleware'=>'auth'], function () {
	Route::get('apiAuth', 'PublicController@index')->name('auth.apiAuth');
});

Route::group(['prefix' => '/user', 'middleware'=>'auth'], function () {
	Route::get('', 'PublicController@index')->name('user.index');
	Route::group(['prefix' => '/node'], function () {
		Route::get('', 'PublicController@index')->name('user.nodeList');
		Route::get('detail/{id}', 'PublicController@index')->name('user.nodeDetail');
		Route::group(['prefix' => '/nodeRelay'], function () {
			Route::get('', 'PublicController@index')->name('user.nodeRelay');
			Route::get('new', 'PublicController@index')->name('user.nodeRelayNew');
			Route::get('edit/{id}', 'PublicController@index')->name('user.nodeRelayEdit');
		});
	});
	Route::group(['prefix' => '/financial'], function () {
		Route::get('purchase', 'PublicController@index')->name('user.purchase');

		Route::get('charge', 'PublicController@index')->name('user.charge');
	});
	Route::group(['prefix' => '/support'], function () {
		Route::get('', 'PublicController@index')->name('user.supportDash');
		Route::get('ticketList', 'PublicController@index')->name('user.ticketList');
		Route::group(['prefix' => '/ticket'], function () {
			Route::get('{id}', 'PublicController@index')->name('user.ticketDetail');
		});
	});
	Route::group(['prefix' => '/settings'], function () {
		Route::get('me', 'PublicController@index')->name('user.userInfoSettings');
		Route::get('connectivity', 'PublicController@index')->name('user.userConnectivitySettings');
	});

	Route::group(['prefix' => '/invite'], function () {
		Route::get('basic', 'PublicController@index')->name('user.invite');
		Route::get('promote', 'PublicController@index')->name('user.promote');
	});
});

Route::group(['prefix' => '/admin', 'middleware'=>['auth']], function () {
	Route::get('', 'PublicController@index')->name('admin.index');
	Route::group(['prefix' => '/user'], function () {
		Route::get('', 'PublicController@index')->name('admin.userList');
		Route::get('edit/{id}', 'PublicController@index')->name('admin.userEdit');
	});
	Route::group(['prefix' => '/node'], function () {
		Route::get('', 'PublicController@index')->name('admin.nodeList');
		Route::get('relay', 'PublicController@index')->name('admin.relayList');
		Route::get('edit/{id}', 'PublicController@index')->name('admin.nodeEdit');
	});
	Route::group(['prefix' => '/marketing'], function () {
		Route::get('', 'PublicController@index')->name('admin.marketingSummary');
		Route::get('inviteCode', 'PublicController@index')->name('admin.marketingInviteCode');
		Route::get('exchangeCode', 'PublicController@index')->name('admin.exchangeCode');
	});
	Route::group(['prefix' => '/group'], function () {
		Route::get('', 'PublicController@index')->name('admin.groupList');
		Route::get('edit/{id}', 'PublicController@index')->name('admin.groupEdit');
	});
	Route::group(['prefix' => '/system'], function () {
		Route::get('', 'PublicController@index')->name('admin.sysConfigList');
		Route::get('connectivityConfig', 'PublicController@index')->name('admin.connectivityConfig');
	});
	Route::group(['prefix' => '/bulletin'], function () {
		Route::get('', 'PublicController@index')->name('admin.BulletinList');
		Route::get('edit/{id}', 'PublicController@index')->name('admin.BulletinEdit');
	});
	Route::group(['prefix' => '/support'], function () {
		Route::get('ticket', 'PublicController@index')->name('admin.ticketList');
		Route::get('ticket/detail/{id}', 'PublicController@index')->name('admin.ticketDetail');
	});

	Route::get('permission', 'PublicController@index')->name('admin.adminList');
});

Route::group(['prefix' => '/sys', 'middleware'=>'auth'], function () {
	Route::get('apiToken', 'Auth\ApiLoginController@getApiToken');
});

Route::get('/{vue_capture?}', function () { return view('index'); })->where('vue_capture', '[\/\w\.-]*');
