<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class FusionBackend extends Model
{
    private static $version = 1;

    public static function send($initiator, $explain, $nodeID, $data){
	    $taskID = Task::initiateTask($initiator, $explain)['id'];
	    $nodeInfo = Node::getNodeInfoByNid($nodeID);
	    $body["version"] = self::$version;
	    $body["data"] = array();
	    array_push($body['data'], $data);
	    $body['task_id'] = $taskID;

	    $body = (object) $body;


	    $http = new Client();

	    $response = $http->post($nodeInfo['real_ip'] . ":" . $nodeInfo['controller_port'], [
		    'headers' => [
			    'key' => $nodeInfo['key'],
			    'Content-Type' => "application/json",
		    ],
		    'body' => json_encode($body)
	    ]);

	    return $taskID;
    }
}
