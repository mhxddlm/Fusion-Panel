<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['initiator', 'detail', 'status', 'result'];

    public static function getTask($taskID = null, $initiator = null, $status = null){
	    return self::when(!is_null($taskID), function($query) use ($taskID){
		    return $query->where('id', $taskID);
	    })->when(!is_null($initiator), function($query) use ($initiator){
		    return $query->where('initiator', $initiator);
	    })->when(!is_null($status), function($query) use ($status){
		    return $query->where('status', $status);
	    })->get();
    }

    public static function initiateTask($initiator, $detail){
    	return self::create([
    		'initiator' => $initiator,
		    'detail' => $detail
	    ]);
    }

    public static function updateTask($taskID, $status, $result){
    	return self::where('id', $taskID)->update([
    		'status' => $status,
		    'result' => $result
	    ]);
    }
}
