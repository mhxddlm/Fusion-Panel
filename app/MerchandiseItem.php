<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchandiseItem extends Model
{
	/**
	 * Return all MerchandiseItem without hidden
	 * -----
	 * @return Eloquent Model
	 */
	public static function getMerchandiseItems(){
		return self::where('hidden', 0)->get();
	}


	public static function getItemInfoById($id){
		return self::where([['hidden', 0], ['id', $id]])->first();
	}
}
