<?php

namespace App\Http\Controllers\Api;

use App\User;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Redis};

class UsersController extends Controller
{
	public function getAll(Request $request)
	{
		$users = User::get();
		
		if ($users->isEmpty())
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'Пока пусто.'
			];
		}
		else
		{
			$status = 200;
			$response = [
				'users' => $users
			];
		}
		return response()->json($response, $status);
	}
	
	public function getById(Request $request)
	{
		$id = $request->id ?? 0;
		$user = User::find($id);
		
		if (!$user)
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'Пользователь не найден.'
			];
		}
		else
		{
			$status = 200;
			$response = [
				'user' => $user->first()
			];
		}
		return response()->json($response, $status);
	}
	
	public function update(Request $request)
	{
		$user = Auth::user();
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['unique:users', 'phone:RU,UA,AZ,BY,MD'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
		
		if ($validator->fails())
		{
			$status = 400;
			$response = [
				'error' => true, 
				'messages' => $validator->errors()
			];
		}
		else
		{
			$user->update($inputs);
			
			$status = 200;
			$response = [
				'user' => $user,
				'message' => 'Данные успешно изменены.'
			];	
		}
		return response()->json($response, $status);
	}

	public function uploadAvatar(Request $request)
	{
		$user = Auth::user();
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
			'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		
		if ($validator->fails())
		{
			$status = 400;
			$response = [
				'error' => true, 
				'messages' => $validator->errors()
			];
		}
		else
		{
			$name = $user->id.'_avatar'.time().'.'.$request->avatar->getClientOriginalExtension();
			$request->avatar->storeAs('avatars', $name);
			$user->image = '/storage/avatars/'.$name;
			$user->save();
			
			$status = 200;
			$response = [
				'url' => '/storage/avatars/'.$name,
				'message' => 'Аватар успешно загружен.'
			];
		}
		return response()->json($response, $status);
	}

}
