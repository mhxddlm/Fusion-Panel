<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    //
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'type', 'content'
	];


	public static function getBulletinDisplay($request){
		$bulletin = self::where('type', $request)->orderBy('id', 'desc')->first();
		if(is_null($bulletin))
			$bulletin = "<h4>Error: 404</h4><p>Cannot locate bulletin called <b>" . $request . "</b></p>";
		else $bulletin = $bulletin->toArray()['content'];
		return $bulletin;
	}

	public static function getBulletinIndex(){
		return self::select('type')->distinct()->get();
	}

	public static function getBulletinListByType($type){
		return self::where('type', $type)->orderBy('id', 'desc')->get();
	}

	public static function createNewBulletin($type, $content){
		return self::create([
			'type' => $type,
			'content' => $content
		]);
	}
	public static function updateBulletin($id, $content){
		return self::where('id', $id)->update([
			'content' => $content
		]);
	}

	public static function deleteBulletin($id){
		if(self::where('code', self::where('id', $id)->value('type'))->count() > 1){
			self::where('id', $id)->delete();
			return  1;
		}
		else
			return -1;
	}
}
