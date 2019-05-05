<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Bulletin;
use App\Node;
use App\NodeGroup;
use App\NodeRelay;
use App\Ticket;
use App\TrafficConvert;
use App\User;
use App\UserConnectivityControl;
use App\UserGroup;
use App\GroupInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserActionController extends Controller
{
	public function newRelay(Request $request){
		if(!Node::checkIfUserHasAccess($request['data']['relaySelected'], $request->user()['id']) &&
			!Node::checkIfUserHasAccess($request['data']['finalSelected'], $request->user()['id']))
			return 404;
		return NodeRelay::newRelay($request->user()['id'], $request['data']['name'], $request['data']['relaySelected'], $request['data']['finalSelected']);
	}

	public function updateRelay(Request $request){
		if(!in_array($request['data']['id'], array_column(NodeRelay::getUserRelay($request->user()['id']), 'id')))
			return -1;
		return NodeRelay::updateRelay($request['data']['id'], $request->user()['id'], $request['data']['name'], $request['data']['relaySelected'], $request['data']['finalSelected']);
	}

	public function deleteRelay(Request $request){
		if(NodeRelay::getRelayInfoByID($request['data'])['user_id'] != $request->user()['id'])
			return 0;
		else
			return NodeRelay::deleteRelay($request['data']);
	}

	public function closeTicket(Request $request){
		if(Ticket::getTicketContent($request['data'])['created_by'] != $request->user()['id'])
			return 0;
		return Ticket::closeTicket($request['data']);
	}

	public function replyTicket(Request $request){
		if(Ticket::getTicketContent($request['data'])['created_by'] != $request->user()['id'])
			return 0;
		return Ticket::replyTicket($request['data']['id'], $request['data']['content'], $request->user()['id']);
	}

	public function updateUserConnectivityInfo(Request $request){
		return UserConnectivityControl::updateUserConnectivityInfo($request['module'], $request->user()['id'], $request['nodeID'], $request['data']);
	}

	public function createUserConnectivityInfo(Request $request){
		return UserConnectivityControl::createUserConnectivityInfo($request['module'], $request->user()['id'], $request['nodeID']);
	}

	public function joinPromoteProgram(Request $request){
		if(!is_null(User::findbyUID($request->user()['id'])['promote_program']))
			return 0;
		else
			return User::joinPromoteProgram($request->user()['id']);
	}
}
