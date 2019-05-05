<?php

namespace App\Http\Controllers\Api\V1\Admin;


use App\Bulletin;
use App\ExchangeCode;
use App\InviteCode;
use App\NodeStatusInfo;
use App\Permission;
use App\SystemConfig;
use App\User;
use App\Node;
use App\UserGroup;
use App\NodeRelay;
use App\NodeGroup;
use App\NodeConnectivityControl;
use App\GroupInfo;
use App\Ticket;
use App\TrafficConvert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

class AdminInterfaceController extends Controller
{
	public function getAdminPermission(Request $request){
		return Permission::getAdminAllPermission($request->user()['id']);
	}
	public function getUserList(Request $request){
		return User::getAllUserWithKeyword($request['search'], $request['num'])->appends(request()->except('page'));
	}

	public function getUserBasicInfo(Request $request){
		$data = User::findbyUID($request['id']);
		$data['status'] = $data['status']? true : false;
		unset($data['email_verified_at']);
		return $data;
	}

	public function getUserGroupInfo(Request $request){
		$data = UserGroup::getUserGroup($request['id'], null);
		foreach ($data as &$t){
			$t['name'] = GroupInfo::getGroupByID($t['group_id'])['name'];
		}
		return $data;
	}

	public function getUserRelayInfo(Request $request){
		$return = [];
		foreach (NodeRelay::getRelay($request['id'], null, null) as $t){
			$data['id'] = $t['id'];
			$data['name'] = $t['name'];
			$data['relayNodeID'] = $t['relay_node'];
			$data['relayNodeName'] = Node::getNodeInfoByNid($t['relay_node'])['name'];
			$data['finalNodeID'] = $t['final_node'];
			$data['finalNodeName'] = Node::getNodeInfoByNid($t['final_node'])['name'];
			array_push($return , $data);
		}
		return $return;
	}

	public function getUserFinancialInfo(Request $request){

	}

	public function getAllGroups(){
		$data = GroupInfo::getAllGroups(true);
		unset($data['order']);
		unset($data['config']);
		unset($data['hidden']);
		return $data;
	}

	public function getNodeList(Request $request){
		return Node::getAllNodeWithKeyword($request['search'])->appends(request()->except('page'));
	}

	public function getNodeStatusInfo(Request $request){
		return NodeStatusInfo::getServerInfoWithDate($request['id'], Carbon::now()->subMinutes(5), Carbon::now());
	}
	public function getNodeBasicInfo(Request $request){
		$data = Node::getNodeInfoByNid($request['id']);
		$data['allow_relay'] = $data['allow_relay']? true : false;
		$data['hidden'] = $data['hidden']? true : false;
		return $data;
	}

	public function getNodeConnectivityConfig(Request $request){
		return NodeConnectivityControl::getNodeConnectivityConfig($request['module'], $request['nodeID']);
	}

	public function getRelayList(Request $request){
		$return = [];
		foreach (NodeRelay::getRelay($request['userID'], $request['relayNode'], $request['finalNode']) as $t){
			$data['id'] = $t['id'];
			$data['userID'] = $t['user_id'];
			$data['username'] = User::findbyUID($t['user_id'])['name'];
			$data['name'] = $t['name'];
			$data['relayNodeID'] = $t['relay_node'];
			$data['relayNodeName'] = Node::getNodeInfoByNid($t['relay_node'])['name'];
			$data['finalNodeID'] = $t['final_node'];
			$data['finalNodeName'] = Node::getNodeInfoByNid($t['final_node'])['name'];
			$data['created_at'] = $t['created_at'];
			array_push($return , $data);
		}
		return $return;
	}

	public function getUserNode(Request $request){
		$data = [];
		foreach (UserGroup::getUserGroup($request['userID'], null) as $group){
			foreach (NodeGroup::getNodeInGroup($group['group_id']) as $t){
				$a = Node::getNodeInfoByNid($t['node_id']);
				$t['name'] =$a['name'];
				$t['allowRelay'] = $a['allow_relay'];
				array_push($data, $t);
			}
		}

		return $data;
	}

	public function getInviteCodeConfig(){
		$t['defaultCodeAmount'] = (int)SystemConfig::getConfigValue('default_invite_code');
		$t['forceInviteRegister'] = (Boolean)((int)SystemConfig::getConfigValue('force_register_w_code'));
		return $t;
	}
	public function getRebateConfig(){
		$t['firstRebatePercent'] = (double)SystemConfig::getConfigValue('first_rebate_percent');
		$t['normalRebatePercent'] = (double)SystemConfig::getConfigValue('normal_rebate_percent');
		$t['rebateStatus'] = (Boolean)((int)SystemConfig::getConfigValue('rebate_status'));
		return $t;
	}

	public function getInviteCodeList(){
		$data = InviteCode::getInviteCode();
		foreach ($data as &$t){
			$t['genName'] = User::findbyUID($t['gen_by'])['name'];
			$t['enable'] = (Boolean)$t['enable'];
		}
		return $data;
	}

	//题外话：加藤惠的套路真的一套一套的啊惊了
	//补番都看傻了，这是何等的骚操作啊我的🐴

	public function getGroupList(){
		$group = GroupInfo::getAllGroups(true);
		foreach ($group as &$t){
			$t['memberCount'] = sizeof(UserGroup::getUsersInGroup($t['id'])->toArray());
			$t['nodeCount'] = sizeof(NodeGroup::getNodeInGroup($t['id'])->toArray());
			$t['usage'] = UserGroup::getGroupUsage($t['id']);
			//追加：第二季第9话真香啊诶我靠
		}
		return $group;
	}

	public function getGroupBasicInfo(Request $request){
		return GroupInfo::getGroupByID($request['id']);
	}

	public function getGroupNodeInfo(Request $request){
		$data = NodeGroup::getNodeInGroup($request['id']);
		foreach ($data as &$t){
			$t['name'] = Node::getNodeInfoByNid($t['node_id'])['name'];
		}
		return $data;
	}

	public function getGroupUserInfo(Request $request){
		$data = UserGroup::getUsersInGroup($request['id']);
		foreach ($data as &$t){
			$t['name'] = User::findbyUID($t['user_id'])['name'];
		}
		return $data;
	}

	public function getSystemConfigList(){
		return SystemConfig::get();
	}

	public function getBulletinTypeIndex(){
		return Bulletin::getBulletinIndex();
	}

	public function getBulletinListByType(Request $request){
		return Bulletin::getBulletinListByType($request['type']);
	}

	public function getTicketList(){
		$data = Ticket::getTicketIndex();
		foreach ($data as &$t){
			$u = User::findbyUID($t['created_by']);
			$t['name'] = $u['name'];
			$t['email'] = $u['email'];
		}
		return $data;
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
			$a['email'] = $u['email'];
			$temp[$t] = $a;
		}
		$data['users'] = $temp;
		return $data;
	}

	public function getExchangeCodeList(){
		return ExchangeCode::getAll();
	}

	public function getAdminList(){
		$data = Permission::getAdmins()->toArray();
		foreach($data as &$a){
			$a['name'] = User::findbyUID($a['user_id'])['name'];
			$a['permissions'] = Permission::getAdminAllPermission($a['user_id'], "name");
		}
		return $data;
	}

	public function getPermissionList(){
		return DB::table('permissions')->get()->groupBy('name');
	}
}
