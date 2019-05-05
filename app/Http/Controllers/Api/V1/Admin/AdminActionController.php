<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Common;
use App\ExchangeCode;
use App\GroupInfo;
use App\InviteCode;
use App\NodeConnectivityControl;
use App\NodeRelay;
use App\Permission;
use App\SystemConfig;
use App\Ticket;
use App\User;
use App\Node;
use App\UserGroup;
use App\Bulletin;
use Carbon\Carbon;
use App\TrafficConvert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminActionController extends Controller
{
    public function updateUserBasicInfo(Request $request){
    	return User::updateUserBasicInfo($request['data']['id'],
		    $request['data']['name'],
		    $request['data']['email'],
		    $request['data']['phone'],
		    TrafficConvert::parseToSpeed($request['data']['speed_limit']),
		    $request['data']['balance'],
		    $request['data']['invite_token_remain'],
		    $request['data']['status']);
    }

    public function addUserGroupInfo(Request $request){
	    $time = empty($request['data']['expireTime']) ? null : Carbon::parse($request['data']['expireTime']);
	    $trafficLimit = empty($request['data']['trafficLimit']) ? null : TrafficConvert::parseToTraffic($request['data']['trafficLimit']);
    	if(empty($request['data']['id']))
		    return UserGroup::addUserGroup($request['data']['userID'],
			    $request['data']['groupID'],
			    $trafficLimit,
			    $time,
			    $request['data']['type']);
    	else{
    		return UserGroup::updateUserGroup($request['data']['id'],
			    $request['data']['userID'],
			    $request['data']['groupID'],
			    $trafficLimit,
			    $time == null ? $time : $time->addDay(),
			    $request['data']['type']);
	    }
    }

    public function updateNodeBasicInfo(Request $request){
	    if(empty($request['data']['id'])){
		    return Node::createNodeBasicInfo($request['data']['name'],
			    $request['data']['address'],
			    $request['data']['real_ip'],
			    $request['data']['area'],
			    $request['data']['description'],
			    $request['data']['rate'],
			    TrafficConvert::parseToSpeed($request['data']['speed_limit']),
			    $request['data']['allow_relay'],
			    $request['data']['hidden']);
	    } else{
		    return Node::updateNodeBasicInfo($request['data']['id'],
			    $request['data']['name'],
			    $request['data']['address'],
			    $request['data']['real_ip'],
			    $request['data']['key'],
			    $request['data']['controller_port'],
			    $request['data']['area'],
			    $request['data']['description'],
			    $request['data']['rate'],
			    TrafficConvert::parseToSpeed($request['data']['speed_limit']),
			    $request['data']['allow_relay'],
			    $request['data']['hidden']);
	    }
    }

	public function updateNodeConnectivityConfig(Request $request){
		return NodeConnectivityControl::updateNodeConnectivityConfig($request['module'], $request['nodeID'], $request['data']);
	}

	public function createNodeConnectivityConfig(Request $request){
		return NodeConnectivityControl::createNodeConnectivityConfig($request['module'], $request['nodeID']);
	}

    public function deleteUserGroup(Request $request){
    	return UserGroup::deleteUserGroup($request['data']);
    }

    public function updateRelayInfo(Request $request){
    	if(empty($request['data']['id'])){
    		return NodeRelay::newRelay($request['data']['userID'],
			    $request['data']['name'],
			    $request['data']['relayNodeID'],
			    $request['data']['finalNodeID']);
	    }else{
    		return NodeRelay::updateRelay($request['data']['id'],
			    $request['data']['userID'],
			    $request['data']['name'],
			    $request['data']['relayNodeID'],
			    $request['data']['finalNodeID']);
	    }
    }

    public function deleteRelayInfo(Request $request){
    	return NodeRelay::deleteRelay($request['data']['id']);
    }

    public function updateInviteCodeInfo(Request $request){
	    $code = empty($request['data']['code']) ? Common::generateRandomString(16) : $request['data']['code'];
	    $genBy = empty($request['data']['gen_by']) || (int)$request['data']['gen_by'] == 0 ? 0 : $request['data']['gen_by'];
    	if(empty($request['data']['id'])){
    		return InviteCode::newInviteCode($code,
			    $request['data']['money_include'],
			    $request['data']['limit'],
			    $request['data']['enable'],
			    $genBy);
	    }else{
		    return InviteCode::updateInviteCode($request['data']['id'],
			    $code,
			    $request['data']['money_include'],
			    $request['data']['limit'],
			    $request['data']['enable']);
	    }
    }

    public function updateGroupBasicInfo(Request $request){
	    if(empty($request['data']['id'])) {
		    return GroupInfo::createGroupInfo($request['data']['name'],
			    $request['data']['description'],
			    $request['data']['order'],
			    TrafficConvert::parseToSpeed($request['data']['speedLimit']),
			    $request['data']['hidden']);
	    }else{
		    return GroupInfo::updateGroupInfo($request['data']['id'],
			    $request['data']['name'],
			    $request['data']['description'],
			    $request['data']['order'],
			    TrafficConvert::parseToSpeed($request['data']['speed_limit']),
			    $request['data']['config'],
			    $request['data']['hidden']);
	    }
    }

    public function updateConfigInfo(Request $request){
    	return SystemConfig::updateConfigValue($request['data']['id'], $request['data']['code'], $request['data']['value']);
    }

    public function updateBulletinInfo(Request $request){
    	if(empty($request['data']['id']))
    		Bulletin::createNewBulletin($request['data']['type'], $request['data']['content']);
    	else
		    Bulletin::updateBulletin($request['data']['id'], $request['data']['content']);
    }

    public function deleteBulletinInfo(Request $request){
	    return Bulletin::deleteBulletin($request['data']);
    }

    public function replyTicket(Request $request){
    	return Ticket::replyTicket($request['data']['id'], $request['data']['content'], $request->user()['id']);
    }

	public function ticketActions(Request $request){
		switch($request['data']['action']){
			case 'close':
				return Ticket::closeTicket($request['data']['id']);
				break;
			case 'lock':
				return Ticket::lockTicket($request['data']['id']);
				break;
			case 'archive':
				return Ticket::archiveTicket($request['data']['id']);
				break;
		}
	}

	public function updateExchangeCode(Request $request){
		$code = empty($request['data']['code']) ? Common::generateRandomString(16) : $request['data']['code'];
		$time = empty($request['data']['expire_at']) ? null : Carbon::parse($request['data']['expire_at']);
    	if(empty($request['data']['id']))
    		ExchangeCode::createExchangeCode($code, $request['data']['money'], $request['data']['limit'], $time);
    	else
		    ExchangeCode::updateExchangeCode($request['data']['id'], $code, $request['data']['money'], $request['data']['limit'], $time);
	}

	public function updateAdminPermission(Request $request){
    	$curr = Permission::where('user_id', $request['data']['id'])->pluck('permission_id')->toArray();
    	$delete = array_diff($curr, $request['data']['permission']);
    	$add = array_diff($request['data']['permission'], $curr);
    	$r['add'] = Permission::addPermission($request['data']['id'], $add);
		$r['del'] = Permission::deletePermission($request['data']['id'], $delete);
    	return $r;
	}
}