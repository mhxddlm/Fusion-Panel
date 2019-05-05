<?php

namespace App\ConnectivityModules;


interface ConnectivityUser
{

	public static function getUserConnectivityInfo($userID = null, $nodeID = null);
}