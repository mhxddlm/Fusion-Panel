<?php

namespace App\Http\Controllers\Api\V1\Node;

use App\FusionBackend;
use App\NodeStatusInfo;
use App\UserConnectivityControl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class NodeController extends Controller
{
    //
	public function index(Request $request){
		Log::debug($request['data']);

		foreach ($request['data'] as $data){
			$module = array_key_first($data);
			Log::debug($data[$module]);
			switch ($module){
				case 'Monitor':
					NodeStatusInfo::newServerInfo($request['node'], $data['Monitor']['cpu'], $data['Monitor']['load1'],
						$data['Monitor']['ram'], $data['Monitor']['inbound'], $data['Monitor']['outbound']);
					break;
			}
		}
	}

	public function register(){
		var_dump(UserConnectivityControl::UpdateUsersModuleJson("shadowsocks", 1, [2, 3, 23]));
	}
}
