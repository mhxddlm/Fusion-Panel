<?php

namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

	protected function authenticated(Request $request, $user){
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
