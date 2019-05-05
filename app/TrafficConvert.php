<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrafficConvert extends Model
{
    //
	public static function autoConvert($request){
		$unit = "B";
		if($request >= 1024) {
			$unit = "KB";
			$request /= 1024;
		}
		if($request >= 1024) {
			$unit = "MB";
			$request /= 1024;
		}
		if($request >= 1024){
			$unit = "GB";
			$request /= 1024;
		}
		if($request >= 1024){
			$unit = "TB";
			$request /= 1024;
		}
		return round($request,2) . $unit;
	}

	public static function parseToSpeed($request){
		if(!isset($request)) return null;
		$request = trim($request);
		$number = doubleval($request);
		$unit = strtoupper(substr($request, -(strlen($request) - strlen((string)$number))));
		switch ($unit){
			case "BPS":
				$base = 1;
				break;
			case "KBPS":
				$base = 1024;
				break;
			case "MBPS":
				$base = 1024 * 1024;
				break;
			case "GBPS":
				$base = 1024 * 1024 * 1024;
				break;
			case "TBPS":
				$base = 1024 * 1024 * 1024 * 1024;
				break;
			case "NLIMITED":
				return null;
			default:
				$base = 0;
		}
		return $number * $base;
	}

	public static function parseToTraffic($request){
		if(empty($request)) return null;
		$request = trim($request);
		$number = doubleval($request);
		$unit = strtoupper(substr($request, -(strlen($request) - strlen((string)$number))));
		switch ($unit){
			case "B":
				$base = 1;
				break;
			case "KB":
				$base = 1024;
				break;
			case "MB":
				$base = 1024 * 1024;
				break;
			case "GB":
				$base = 1024 * 1024 * 1024;
				break;
			case "TB":
				$base = 1024 * 1024 * 1024 * 1024;
				break;
			case "NLIMITED":
				return null;
			default:
				$base = 0;
		}
		return $number * $base;
	}
}
