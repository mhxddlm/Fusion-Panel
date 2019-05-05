<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NodeRelay extends Model
{
	protected $fillable = [
		'user_id', 'name', 'relay_node', 'final_node'
	];
	/**
	 * Get all relay under this filter rule
	 * -----
	 * @param $userID userID
	 * @param $relayNode relayNode ID
	 * @param $finalNode finalNode ID
	 * @return Array
	 */
	public static function getRelay($userID, $relayNode, $finalNode){
		return self::when(!empty($userID), function($query) use ($userID){
			return $query->where('user_id', $userID);
		})->when(!empty($relayNode), function($query) use ($relayNode){
			return $query->where('relay_node', $relayNode);
		})->when(!empty($finalNode), function($query) use ($finalNode){
			return $query->where('final_node', $finalNode);
		})->get();
	}

	/**
	 * Return relay info by relayID
	 * -----
	 * @param $id relayID
	 * @return Array
	 */
	public static function getRelayInfoByID($id){
		return self::where('id', $id)->first();
	}

	/**
	 * Add new relay to db
	 * -----
	 * @param $userID, userID
	 * @param $name, relayName
	 * @param $relayNode, relayNode
	 * @param $finalNode, finalNode
	 * @return int
	 * -2: This user modified data with some skills, reject.
	 * -1: This user has already create a rule with this relay node.
	 * 0: relayNode does not allow relay.
	 * 1: success
	 */
	public static function newRelay($userID, $name, $relayNode, $finalNode){
		if($relayNode == $finalNode)
			return -2;

		if(self::where([['relay_node',$relayNode], ['user_id', $userID]])->exists())
			return -1;

		if(!Node::checkIfNodeAllowRelay($relayNode))
			return 0;

		self::create([
			'user_id' => $userID,
			'name' => $name,
			'relay_node' => $relayNode,
			'final_node' => $finalNode
		]);
		return 1;
	}

	/**
	 * update an existing relay
	 * -----
	 * @param $id, relayID
	 * @param $userID, userID
	 * @param $name, relayName
	 * @param $relayNode, relayNode
	 * @param $finalNode, finalNode
	 * @return int
	 * -2: This user is using tricks to bypass front end
	 * -1: The relay id does not belong to the requested user
	 * 0: This node does not allow relay
	 * 1: Success
	 */
	public static function updateRelay($id, $userID, $name, $relayNode, $finalNode){
		if($relayNode == $finalNode)
			return -2;

		if(self::where('id', $id)->first()['user_id'] != $userID)
			return -1;

		if(!Node::checkIfNodeAllowRelay($relayNode))
			return 0;

		self::where('id',$id)->update(['name' => $name, 'relay_node' => $relayNode, 'final_node' => $finalNode]);
		return 1;
	}

	/**
	 * delete a relay
	 * -----
	 * @param $id
	 * @return int
	 * -1: The relay DNE
	 * 1: Success
	 */
	public static function deleteRelay($id){
		return self::where('id', $id)->exists() ? self::where('id', $id)->delete() : -1;
	}
}
