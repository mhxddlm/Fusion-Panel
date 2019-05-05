<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NodeStatusInfo extends Model
{
    //
	protected $fillable = [
		'node_id', 'cpu', 'memory', 'load', 'upload', 'download'
	];

	/**
	 * Return newest numbered row of the data from this node.
	 * @param $id
	 * @param $count
	 * @return mixed
	 */
	public static function getServerInfoWithNewestRow($id, $count){
		return self::select('node_id', 'cpu', 'memory', 'load', 'upload', 'download', 'created_at')
			->where('node_id', $id)->orderBy('created_at', 'desc')->take($count)->get();
	}

	public static function getServerInfoWithDate($id, $from = null, $end = null){
		return self::select('node_id', 'cpu', 'memory', 'load', 'upload', 'download', 'created_at')
			->where('node_id', $id)
			->whereBetween('created_at', [$from, $end])->get();
	}

	public static function newServerInfo($id, $cpu, $load, $ram, $inbound, $outbound){
		self::create([
			'node_id' => $id,
			'cpu' => $cpu,
			'load' => $load,
			'memory' => $ram,
			'download' => $inbound,
			'upload' => $outbound
		]);
	}
}
