<?php

namespace App\Http\Controllers\Api\V1\User;

use App\NodeConnectivityControl;
use App\QA;
use App\SystemConfig;
use App\User;
use App\Bulletin;
use App\MerchandiseItem;
use App\Node;
use App\NodeGroup;
use App\NodeRelay;
use App\NodeStatusInfo;
use App\TrafficConvert;
use App\UserConnectivityControl;
use App\UserGroup;
use App\GroupInfo;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserInterfaceController extends Controller
{
	/**
	 * Get Bulletin using bulletin type
	 * @param Request $request
	 * @return string
	 */
	public function getBulletin(Request $request){
		return Bulletin::getBulletinDisplay($request['type']);
	}

	public function getSysConfig(Request $request){
		//TODO: authentication required here
		return SystemConfig::getConfigValue($request['code']);
	}

	public function getUserGroupInfo(Request $request){
		$data = UserGroup::getUserGroup($request->user()['id']);
		foreach ($data as &$t){
			$t['name'] = GroupInfo::getGroupByID($t['group_id'])['name'];
		}
		return $data;
	}

	public function getGroupInfo(Request $request){
		return GroupInfo::getGroupByID($request['id']);
	}

	public function getNodeInGroup(Request $request){
		//Check if user has access to this group
		if(!in_array($request['group'], array_column(UserGroup::getUserGroup($request->user()['id'], null)->toArray(), 'group_id'))){
			return 404;
		}
		$data = [];
		$nodes = NodeGroup::getNodeInGroup($request['group']);
		foreach ($nodes as $node){
			$nodeInfo = Node::getNodeInfoByNid($node['node_id']);
			if($nodeInfo['hidden'])
				continue;
			$t['id'] = $node['node_id'];
			$t['name'] = $nodeInfo['name'];
			$t['des'] = $nodeInfo['description'];
			$t['area'] = $nodeInfo['area'];
			$status = NodeStatusInfo::getServerInfoWithNewestRow($node['node_id'], 1)->toArray();
			$t['status'] = $status == null ? $t['status'] = null : $status[0];
			array_push($data, $t);
		}
		return $data;
	}

	public function getNodeInfo(Request $request){
		//Check if user has access to this node
		if(!Node::checkIfUserHasAccess($request['nodeID'], $request->user()['id']))
			return 404;

		$nodeInfo = Node::getNodeInfoByNid($request['nodeID']);
		if($nodeInfo['hidden'])
			return 404;
		unset($nodeInfo['created_at']);
		unset($nodeInfo['updated_at']);
		unset($nodeInfo['hidden']);
		return $nodeInfo;
	}

	public function checkIfNodeAllowRelay(Request $request){
		return Node::checkIfNodeAllowRelay($request['node_id']);
	}


	public function getUserRelay(Request $request){
		$t = NodeRelay::getRelay($request->user()['id'], null, null);
		foreach ($t as &$a){
			$a['relayNodeName'] = Node::getNodeInfoByNid($a['relay_node'])['name'];
			$a['finalNodeName'] = Node::getNodeInfoByNid($a['final_node'])['name'];
		}
		return $t;
	}

	public function getRelayInfo(Request $request){
		if(!in_array($request['id'], array_column(NodeRelay::getUserRelay($request->user()['id']), 'id')))
			return -1;
		$t = NodeRelay::getRelayInfoByID($request['id']);
		$t['relayNodeName'] = Node::getNodeInfoByNid($t['relay_node'])['name'];
		$t['finalNodeName'] = Node::getNodeInfoByNid($t['final_node'])['name'];
		return $t;
	}

	public function getMerchandiseItems(){
		return MerchandiseItem::getMerchandiseItems();
	}

	public function getTicketList(Request $request){
		return Ticket::getTicketIndex(false,  $request->user()['id']);
	}

	public function getTicketDetail(Request $request){
		$data['content'] = Ticket::getTicketContent($request['id']);
		$data['replies'] = Ticket::getTicketReplies($request['id']);
		$data['users'] = Ticket::getInvolvedUsers($request['id']);
		$temp = [];
		foreach ($data['users'] as $t){
			$u = User::findbyUID($t);
			$a['id'] = $t;
			$a['name'] = $u['name'];
			$a['email'] = md5($u['email']);
			$temp[$t] = $a;
		}
		$data['users'] = $temp;
		return $data;
	}

	public function getQAList(){
		return QA::getQAList();
	}

	public function getUserBasicInfo(Request $request){
		return User::findbyUID($request->user()['id']);
	}

	public function getNodeConnectivityConfig(Request $request){
		return NodeConnectivityControl::getNodeConnectivityConfig($request['module'], $request['nodeID']);
	}

	public function getNodeConnectivityInfo(Request $request){
		return UserConnectivityControl::getUserConnectivityInfo($request['module'], $request->user()['id'], $request['nodeID']);
	}

	public function getPromoteInfo(Request $request){
		$data['promoteTransferLimit'] = (Double)SystemConfig::getConfigValue('promote_money_trasnfer');
		$data['promoteConfig']['rebatePercent'] = (Double)SystemConfig::getConfigValue('normal_rebate_percent');
		$data['promoteConfig']['newPromoteUserDefaultMoney'] = (Double)SystemConfig::getConfigValue('new_promote_user_default_money');
		$data['member'] = User::getUserInvitedUsers($request->user()['id']);
		foreach ($data['member'] as &$t){
			$t['email'] = md5($t['email']);
		}
		return $data;
	}

	public function getInviteInfo(Request $request){
		$data['member'] = User::getUserInvitedUsers($request->user()['id']);
		foreach ($data['member'] as &$t){
			$t['email'] = md5($t['email']);
		}
		return $data;
	}
}
