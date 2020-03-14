<?php

namespace App\Http\Controllers\Api;

use App\User; 
use Validator;
use LVR\Phone\Phone;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Redis, Auth};
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
	public $url,
		   $status = 200, 
		   $response = [];
	
	public function register(Request $request) {
		$url = 'https://'.config('sms.email').':'.config('sms.apiKey').'@gate.smsaero.ru/v2/sms/send?';
		
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['unique:users', 'phone:RU,UA,AZ,BY,MD'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
		
		if ($validator->fails())
		{
			$this->status = 401;
			$this->response = [
				'error' => true, 
				'messages' => $validator->errors()
			];
		}
		else
		{
			$code = rand(1000, 9999);
			$args = [
				'number' => $inputs['phone'],
				'text' => 'Код для подтверждения регистрации: '.$code,
				'sign' => 'SMS Aero',
				'channel' => ( !preg_match('/^(\+7|7|8).+/ui', $inputs['phone']) ? 'INTERNATIONAL' : 'INFO' ),
			];
			$link = $url.http_build_query($args, '', '&');
			
			$client = new \GuzzleHttp\Client();
			$response = $client->request('GET', $link);
			$response = $response->getBody()->getContents();
			
			$json = json_decode($response) ?? false;
			
			if (!$json)
			{
				$this->status = 500;
				$this->response = [
					'error' => true,
					'message' => 'Смс сервис временно недоступен.'
				];
			}
			else
			{
				if (!$json->success)
				{
					$this->status = 500;
					$this->response = [
						'error' => true,
						'response' => $json,
						'message' => 'Смс не было отправлено.'
					];
				}
				else
				{
					$token = Str::random(50);
					Redis::set('user:'.$token, json_encode(['code' => $code, 'data' => $inputs]), 'EX', 60*1000);
					
					$this->response = [
						'token' => $token,
						'message' => 'Смс отправлено к вам на телефон.'
					];
				}
			}
		}
		return response()->json($this->response, $this->status);
	}

	public function registerConfirm(Request $request) {
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
			'code' => ['required', 'numeric'],
			'token' => ['required'],
		]);
		
		if ($validator->fails())
		{
			$this->status = 401;
			$this->response = [
				'error' => true, 
				'messages' => $validator->errors()
			];
		}
		else
		{
			$json = Redis::get('user:'.$inputs['token']) ?? false;
			$user = $json ? json_decode($json, true) : false;
			
			if (!$json)
			{
				$this->status = 400;
				$this->response = [
					'error' => true,
					'message' => 'Срок действия токена истек, код недействителен.'
				];
			}
			else
			{
				if (intval($inputs['code']) !== intval($user['code']))
				{
					$this->status = 400;
					$this->response = [
						'error' => true,
						'message' => 'Код введен неверно.'
					];
				}
				else
				{
					$data = $user['data'];
					Redis::del('user:'.$inputs['token']);
						
					$newUser = User::create([
						'name' => $data['name'],
						'phone' => $data['phone'],
						'password' => bcrypt($data['password'])
					]);
					$token = $newUser->createToken('AppName')->accessToken;
					
					$this->starus = 200;
					$this->response = [
						'user' => $newUser,
						'token' => $token,
						'message'=> 'Регистрация прошла успешно.'
					];
				}
			}
		}
		return response()->json($this->response, $this->status);
	}
	
	public function recovery(Request $request) {
		$url = 'https://'.config('sms.email').':'.config('sms.apiKey').'@gate.smsaero.ru/v2/sms/send?';
		
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
			'phone' => ['phone:RU,UA,AZ,BY,MD'],
		]);
		
		if ($validator->fails())
		{
			$this->status = 400;
			$this->response = [
				'error' => true, 
				'messages' => $validator->errors()
			];
		}
		else if (!$user = User::where('phone', $inputs['phone'])->orWhere('phone', 'like', '%'.$inputs['phone'])->first())
		{
			$this->status = 400;
			$this->response = [
				'error' => true,
				'message' => 'Пользователь с таким номером не найден.'
			];
		}
		else
		{
			$code = rand(1000, 9999);
			$token = Str::random(50);
			
			$args = [
				'number' => $inputs['phone'],
				'text' => 'Код для сброса пароля: '.$code,
				'sign' => 'SMS Aero',
				'channel' => ( !preg_match('/^(\+7|7|8).+/ui', $inputs['phone']) ? 'INTERNATIONAL' : 'INFO' ),
			];
			$link = $url.http_build_query($args, '', '&');
			
			$client = new \GuzzleHttp\Client();
			$response = $client->request('GET', $link);
			$response = $response->getBody()->getContents();
			
			$json = json_decode($response) ?? false;
			
			if (!$json)
			{
				$this->status = 400;
				$this->response = [
					'error' => true,
					'response' => $response,
					'message' => 'Смс сервис временно недоступен.'
				];
			}
			else
			{
				if (!$json->success)
				{
					$this->status = 400;
					$this->response = [
						'error' => true,
						'response' => $json,
						'message' => 'Смс не было отправлено.'
					];
				}
				else
				{
					Redis::set('recovery:'.$token, json_encode(['code' => $code, 'phone' => $inputs['phone']]), 'EX', 60*1000);
					
					$this->response = [
						'token' => $token,
						'message' => 'Смс отправлено к вам на телефон.'
					];
				}
			}
		}
		return response()->json($this->response, $this->status);
	}
	
	public function recoveryConfirm(Request $request)
	{
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
			'code' => ['required', 'numeric'],
			'token' => ['required'],
		]);
		
		if ($validator->fails())
		{
			$this->status = 401;
			$this->response = [
				'error' => true, 
				'messages' => $validator->errors()
			];
		}
		else
		{
			$json = Redis::get('recovery:'.$inputs['token']) ?? false;
			$data = $json ? json_decode($json, true) : false;
			
			if (!$json)
			{
				$this->status = 400;
				$this->response = [
					'error' => true,
					'message' => 'Срок действия токена истек, код недействителен.'
				];
			}
			else
			{
				if (intval($inputs['code']) !== intval($data['code']))
				{
					$this->status = 400;
					$this->response = [
						'error' => true,
						'message' => 'Код введен неверно.'
					];
				}
				else
				{
					$pass = Str::random(8);
					$phone = $data['phone'];
					
					$user = User::where('phone', $phone)->orWhere('phone', 'like', '%'.$phone)->first();
					
					if (empty($user))
					{
						$this->status = 400;
						$this->response = [
							'error' => true,
							'message' => 'Пользователь с таким номером не найден.'
						];
					}
					else
					{
						Redis::del('recovery:'.$inputs['token']);
						$user->password = bcrypt($pass);
						$user->save();

						$this->starus = 200;
						$this->response = [
							'password' => $pass,
							'message'=> 'Пароль успешно сброшен.'
						];
					}
				}
			}
		}
		return response()->json($this->response, $this->status);
	}
	
	public function login(Request $request)
	{
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
			'phone' => ['phone:RU,UA,AZ,BY,MD'],
			'password' => ['required', 'string', 'min:8'],
		]);

		if ($validator->fails())
		{
			$this->status = 400;
			$this->response = [
				'error' => true, 
				'messages' => $validator->errors()
			];
		}
		elseif (Auth::attempt(['phone' => $inputs['phone'], 'password' => $inputs['password']]))
		{
			$user = Auth::user();
			$user->token = $user->createToken('AppName')->accessToken;
			$user->save();
			
			$this->status = 200;
			$this->response = [
				'user' => $user,
				'token' => $user->token,
				'message'=> 'Авторизация прошла успешно.'
			];
			
		}
		else
		{
			$this->status = 401;
			$this->response = [
				'error' => true, 
				'messages' => 'Номер или пароль неверный.'
			];
		}
		return response()->json($this->response, $this->status); 
	}
	
	public function getUser()
	{
		return response()->json(['data' => Auth::user()], 200); 
	}
}
