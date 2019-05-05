<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
	protected $fillable = [
		'ticket_id', 'content', 'replied_by'
	];
}
