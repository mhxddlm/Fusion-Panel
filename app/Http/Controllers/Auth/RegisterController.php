<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\InviteCode;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
    	return Validator::make($data, [
            'name' => 'required|string|max:255||unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:7|confirmed',
	        'code' => ['required', function($attribute, $value, $fail){
        	    if(InviteCode::checkIfUsable($value) != 1)
        	    	return $fail($attribute . ' is invalid');
	        }]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
	        'invited_by' => InviteCode::where('code', $data['code'])->value('gen_by')
        ]);
    }

    protected function registered(Request $request, $user)
    {
    	InviteCode::inviteCodeUsed($request['code']);
	    $http = new Client();
	    $response = $http->post(env('API_URL') . '/oauth/token', [
		    'form_params' => [
			    'grant_type' => 'password',
			    'client_id' => env('API_CLIENT_ID'),
			    'client_secret' => env('API_CLIENT_SECRET'),
			    'username' => $request['email'],
			    'password' => $request['password'],
			    'scope' => '*',
		    ],
	    ]);
	    $apiResponse = json_decode((string) $response->getBody(), true);
	    session(['api'=> $apiResponse]);
	    session(['api-token'=> $apiResponse['access_token']]);
    }
}
