<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QA extends Model
{
	protected $table = 'qas';
	protected $fillable = [
		'question', 'content'
	];

	public static function getQAList(){
		return self::select('id', 'question', 'content')->orderBy('useful', 'sec')->get();
	}
}
