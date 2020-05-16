<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
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
	protected $redirectTo = RouteServiceProvider::HOME;

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
		$input = $request->all();
		$fieldType = (isNumber($request->phone_or_email) ? 'phone' : 'email');
		
		$validations = [
			'email' => ['required', 'email'],
			'phone' => ['phone:RU,UA,AZ,BY,MD']
		];
        
		$this->validate($request, [
			'phone_or_email' => $validations[$fieldType],
			'password' => ['required', 'string', 'min:8'],
			'remember_me' => ['numeric', 'digits_between:0,1']
		]);
		
		if(auth()->attempt([$fieldType => $input['phone_or_email'], 'password' => $input['password']], (bool) $request->remember_me))
		{
			return redirect()->route('home');
		} 
		else
		{
            dd($fieldType, $request->all());
            
			if ($fieldType == 'email')
				return redirect()->back()->withErrors(['phone_or_email' => ['E-mail или пароль неверный.']]);
			else
				return redirect()->back()->withErrors(['phone_or_email' => ['Телефон или пароль неверный.']]);
		}
    }
}
