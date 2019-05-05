<?php

namespace App;

use http\Env\Request;
use Illuminate\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'invited_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function findbyUID($request){
    	return self::where('id', $request)->first();
    }

    public static function findbyEmail($request){
    	return self::where('email', $request)->first();
    }

	/**
	 * Return all users with compatible search result from column 'name', 'email', and joined with name for 'invited_by'.
	 * -----
	 * @param $request String
	 * @return Eloquent Model
	 */
    public static function getAllUserWithKeyword($keyword, $num){
    	return self::where('name', 'like', '%' . $keyword .'%')->
		    orWhere('email', 'like', '%' . $keyword .'%')->
	        paginate($num);
    }

	/**
	 * Update user's basic info using user_id
	 * -----
	 * @param $id Integer
	 * @param $name String
	 * @param $email Email
	 * @param $phone Integer
	 * @param $speedLimit Integer
	 * @param $balance Double
	 * @param $inviteCode Integer
	 * @param $status Boolean
	 * @return int
	 * 0: User not found
	 * 1: Success
	 */
    public static function updateUserBasicInfo($id, $name, $email, $phone, $speedLimit, $balance, $inviteCode, $status){
    	if(self::where('id', $id)->doesntExist())
    		return 0;
    	return self::where('id', $id)->update([
    		'name'=> $name,
		    'email'=> $email,
		    'phone' => $phone,
		    'status'=> $status,
		    'speed_limit'=> $speedLimit,
		    'balance'=> $balance,
		    'invite_token_remain'=> $inviteCode]);
    }

    public static function getUserInvitedUsers($userID){
    	return self::select('id', 'name', 'email', 'invited_by')->where('invited_by', $userID)->get();
    }

    public static function joinPromoteProgram($userID){
    	return self::where('id', $userID)->update(['promote_program' => Common::generateRandomString(8)]);
    }
}
