<?php


namespace App\ConnectivityModules;


interface ConnectivityConfig
{

	public static function getConnectivityConfig($nodeID);
}