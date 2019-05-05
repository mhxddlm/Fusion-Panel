<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserInfosController extends Controller
{
    //
	public function getUserInfo(Request $request){
		$data = $request->user();
		$data['permission'] = Permission::getAdminAllPermission($data['id']);
		return response()->json($data);
	}
}
