<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if($validator->fails()) {
            return response([
                "message" => "The given data was invalid.",
                "errors" => $validator->errors(),
            ], 422);
        }
 
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('user')->accessToken;

            return response([
                "token" => $token,
                "token_type" => "bearer",
                "expires_at" => Auth::user()->accessTokens->first()->expires_at
            ], 200);
        } else {
            return response([
                "message" => "The given data was invalid.",
                "errors" => array("email" => ["These credentials do not match our records."]),
            ], 422);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
           Auth::user()->AauthAcessToken()->delete();
        }
    }
}
