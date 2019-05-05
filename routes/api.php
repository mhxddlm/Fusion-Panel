<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
	Route::get('userAuth/getCodeInfo','AuthActionController@getCodeInfo');
	Route::post('userAuth/checkEmailUsername','AuthActionController@checkEmailUsername');
	Route::post('userAuth/logout','AuthActionController@logout');

	Route::group(['prefix' => '/node', 'namespace' => 'Node'], function () {
		Route::post('', 'NodeController@index');
		Route::post('register', 'NodeController@register');
	});
});

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.', 'middleware' => ['auth:api']], function () {
	Route::group(['prefix' => '/user', 'namespace' => 'User'], function () {
		Route::get('getSysConfig', 'UserInterfaceController@getSysConfig');
		Route::get('userInfo', 'UserInfosController@getUserInfo');
		Route::get('getBulletin', 'UserInterfaceController@getBulletin');

		Route::get('getUserGroupInfo', 'UserInterfaceController@getUserGroupInfo');
		Route::get('getGroupInfo', 'UserInterfaceController@getGroupInfo');
		Route::get('getNodeInGroup', 'UserInterfaceController@getNodeInGroup');
		Route::get('getNodeInfo', 'UserInterfaceController@getNodeInfo');
		Route::post('checkIfNodeAllowRelay', 'UserInterfaceController@checkIfNodeAllowRelay');
		Route::post('newRelay', 'UserActionController@newRelay');
		Route::post('updateRelay', 'UserActionController@updateRelay');
		Route::post('deleteRelay', 'UserActionController@deleteRelay');
		Route::get('getUserRelay', 'UserInterfaceController@getUserRelay');
		Route::get('getRelayInfo', 'UserInterfaceController@getRelayInfo');

		Route::get('getMerchandiseItems', 'UserInterfaceController@getMerchandiseItems');

		Route::get('getTicketList', 'UserInterfaceController@getTicketList');
		Route::get('getTicketDetail', 'UserInterfaceController@getTicketDetail');
		Route::get('getQAList', 'UserInterfaceController@getQAList');
		Route::post('closeTicket', 'UserActionController@closeTicket');
		Route::post('replyTicket', 'UserActionController@replyTicket');

		Route::get('getUserBasicInfo', 'UserInterfaceController@getUserBasicInfo');
		Route::get('getNodeConnectivityConfig', 'UserInterfaceController@getNodeConnectivityConfig');
		Route::get('getNodeConnectivityProfile', 'UserInterfaceController@getNodeConnectivityInfo');
		Route::post('updateUserConnectivityProfile', 'UserActionController@updateUserConnectivityInfo');
		Route::post('createUserConnectivityProfile', 'UserActionController@createUserConnectivityInfo');

		Route::get('getPromoteInfo', 'UserInterfaceController@getPromoteInfo');
		Route::get('getInviteInfo', 'UserInterfaceController@getInviteInfo');
		Route::post('joinPromoteProgram', 'UserActionController@joinPromoteProgram');

	});

	Route::group(['prefix' => '/admin', 'namespace' => 'Admin', 'middleware' => ['can:logAdmin,App\Permission']], function () {
		Route::get('getAdminPermission', 'AdmianInterfaceController@getAdminPermission');

		Route::get('getUserList', 'AdminInterfaceController@getUserList');

		Route::group(['middleware' => ['can:UserControlPermission,App\Permission']], function () {
			Route::get('getUserBasicInfo', 'AdminInterfaceController@getUserBasicInfo');

			Route::post('updateUserBasicInfo', 'AdminActionController@updateUserBasicInfo');
		});

		Route::group(['middleware' => ['can:GroupControlPermission,App\Permission']], function () {
			Route::get('getUserGroupInfo', 'AdminInterfaceController@getUserGroupInfo');
			Route::get('getAllGroups', 'AdminInterfaceController@getAllGroups');
			Route::get('getGroupList', 'AdminInterfaceController@getGroupList');
			Route::get('getGroupBasicInfo', 'AdminInterfaceController@getGroupBasicInfo');
			Route::get('getGroupNodeInfo', 'AdminInterfaceController@getGroupNodeInfo');
			Route::get('getGroupUserInfo', 'AdminInterfaceController@getGroupUserInfo');

			Route::post('updateGroupBasicInfo', 'AdminActionController@updateGroupBasicInfo');
			Route::post('addUserGroupInfo', 'AdminActionController@addUserGroupInfo');
			Route::post('deleteUserGroup', 'AdminActionController@deleteUserGroup');
		});

		Route::group(['middleware' => ['can:RelayControlPermission,App\Permission']], function () {
			Route::get('getRelayList', 'AdminInterfaceController@getRelayList');
			Route::get('getUserRelayInfo', 'AdminInterfaceController@getUserRelayInfo');

			Route::post('addRelayInfo', 'AdminActionController@updateRelayInfo');
			Route::post('deleteRelayInfo', 'AdminActionController@deleteRelayInfo');
		});

		Route::group(['middleware' => ['can:NodeControlPermission,App\Permission']], function () {
			Route::get('getUserNode', 'AdminInterfaceController@getUserNode');

			Route::get('getNodeList', 'AdminInterfaceController@getNodeList');
			Route::get('getNodeStatusInfo', 'AdminInterfaceController@getNodeStatusInfo');
			Route::get('getNodeBasicInfo', 'AdminInterfaceController@getNodeBasicInfo');
			Route::get('getNodeConnectivityConfig', 'AdminInterfaceController@getNodeConnectivityConfig');

			Route::post('updateNodeBasicInfo', 'AdminActionController@updateNodeBasicInfo');
			Route::post('updateNodeConnectivityConfig', 'AdminActionController@updateNodeConnectivityConfig');
			Route::post('createNodeConnectivityConfig', 'AdminActionController@createNodeConnectivityConfig');
		});

		Route::group(['middleware' => ['can:MarketingControlPermission,App\Permission']], function () {
			Route::get('getInviteCodeConfig', 'AdminInterfaceController@getInviteCodeConfig');
			Route::get('getRebateConfig', 'AdminInterfaceController@getRebateConfig');
			Route::get('getInviteCodeList', 'AdminInterfaceController@getInviteCodeList');
			Route::get('getExchangeCodeList', 'AdminInterfaceController@getExchangeCodeList');
			Route::get('getExchangeCodeDetail', 'AdminInterfaceController@getExchangeCodeDetail');
			Route::get('getUserFinancialInfo', 'AdminInterfaceController@getUserFinancialInfo');

			Route::post('updateExchangeCode', 'AdminActionController@updateExchangeCode');
			Route::post('addInviteCodeInfo', 'AdminActionController@updateInviteCodeInfo');

		});

		Route::group(['middleware' => ['can:SystemControlPermission,App\Permission']], function () {
			Route::get('getSystemConfigList', 'AdminInterfaceController@getSystemConfigList');

			Route::post('updateConfigInfo', 'AdminActionController@updateConfigInfo');
		});

		Route::group(['middleware' => ['can:BulletinControlPermission,App\Permission']], function () {
			Route::get('getBulletinTypeIndex', 'AdminInterfaceController@getBulletinTypeIndex');
			Route::get('getBulletinListByType', 'AdminInterfaceController@getBulletinListByType');

			Route::post('updateBulletinInfo', 'AdminActionController@updateBulletinInfo');
			Route::post('deleteBulletinInfo', 'AdminActionController@deleteBulletinInfo');
		});

		Route::group(['middleware' => ['can:TicketControlPermission,App\Permission']], function () {
			Route::get('getTicketList', 'AdminInterfaceController@getTicketList');
			Route::get('getTicketDetail', 'AdminInterfaceController@getTicketDetail');

			Route::post('replyTicket', 'AdminActionController@replyTicket');
			Route::post('ticketActions', 'AdminActionController@ticketActions');
		});

		Route::group(['middleware' => ['can:AdminControlPermission,App\Permission']], function () {
			Route::get('getAdminList', 'AdminInterfaceController@getAdminList');
			Route::get('getPermissionList', 'AdminInterfaceController@getPermissionList');

			Route::post('updateAdminPermission', 'AdminActionController@updateAdminPermission');
		});
	});
});