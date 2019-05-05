<?php

namespace App;

use App\TicketReply;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

	protected $fillable = [
		'title', 'content', 'created_by'
	];

	/**
	 * Return all ticket index with options
	 * @param bool $showClosed
	 * @param int $uid
	 * @return mixed
	 */
    public static function getTicketIndex($showClosed = false, $uid = null){
    	return self::when(!$showClosed, function($query){
		    return $query->where('status', '<>', -1);
	    })->when(!empty($uid), function($query) use ($uid){
		    return $query->where('created_by', $uid);
	    })->orderBy('created_at', 'desc')->get();
    }

    public static function getTicketContent($id){
    	return self::where('id', $id)->first();
    }

    public static function getTicketReplies($id){
    	return TicketReply::where('ticket_id', $id)->get();
    }

    public static function getInvolvedUsers($id){
    	$data = [];
    	array_push($data, self::select('created_by')->where('id', $id)->value('created_by'));
    	foreach (TicketReply::select('replied_by')->distinct()->pluck('replied_by') as $t){
    		if($t != $data[0])
    		array_push($data, $t);
	    }
	    return $data;
    }


    public static function replyTicket($id, $content, $userID){
    	self::where('id', $id)->update(['status' => 1]);
    	TicketReply::create([
    		'ticket_id' => $id,
		    'content' => $content,
		    'replied_by' => $userID
	    ]);
    }
    public static function closeTicket($id){
    	return self::where('id', $id)->update(['status' => 0]);
    }

	public static function lockTicket($id){
		return self::where('id', $id)->update(['status' => -1]);
	}

	public static function archiveTicket($id){
		return self::where('id', $id)->update(['status' => -2]);
	}
}
