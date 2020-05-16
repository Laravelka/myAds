<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Support\Facades\{Storage, Redis};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use DB;

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
			$users = array_map(function($item) {
				$item->image = '/storage'.$item->image;
				
				return $item;
			}, $users->toArray());
			
			$status = 200;
			$response = [
				'users' => $users
			];
		}
		return response()->json($response, $status);
	}
	
	public function create(Request $request)
	{
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
			'role' => ['required', 'in:user,admin'],
			'email' => ['unique:users', 'email'],
			'name' => ['required', 'string', 'max:120'],
            'phone' => ['unique:users', 'phone:RU,UA,AZ,BY,MD'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
			'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
			if ($request->has('email'))
				$inputs['email'] = $request->email;
			
			if ($request->has('phone'))
				$inputs['phone'] = $request->phone;
			
			$inputs['password'] = bcrypt($request->password);
			
			if ($request->has('image'))
			{
				$name = 'avatar_'.time().'.'.$request->image->getClientOriginalExtension();
				$request->image->storeAs('avatars', $name);
				$inputs['image'] = '/storage/avatars/'.$name;
			}
			$user = User::create($inputs);
			
			$status = 200;
			$response = [
				'user' => $user,
				'message' => 'Пользователь успешно добавлен!'
			];
		}
		return response()->json($response, $status);
	}
	
	public function update(Request $request)
	{
		$id = $request->id ?? 0;
		$validator = Validator::make($request->all(), [
			'role' => ['required', 'in:user,admin'],
			'email' => ['email'],
			'name' => ['required', 'string', 'max:120'],
            'phone' => ['phone:RU,UA,AZ,BY,MD'],
			'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => ['min:8'],
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
			$user = User::find($id);
			$user->role = $request->role;
			$user->name = $request->name;
			$user->email = $request->email;
			$user->phone = $request->phone;
			$user->password = bcrypt($request->password);
			
			if ($request->has('image'))
			{
				$name = $user->id.'_avatar_'.time().'.'.$request->image->getClientOriginalExtension();
				$request->image->storeAs('avatars', $name);
				$user->image = '/storage/avatars/'.$name;
			}
			$user->save();
			
			$status = 200;
			$response = [
				'message' => 'Пользователь успешно отредактипрован!'
			];
		}
		return response()->json($response, $status);
	}
	
	public function delete($id)
	{
		$user = User::find($id ?? 0);
		
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
			$boolean = (
				$user->image == '/img/default-avatar.png' ? true : Storage::disk('public')->delete(str_replace('/storage', '', $user->image))
			);
			
			if ($boolean)
			{
				$user->orderedMarler()->delete();
				$user->delete();
				
				$status = 200;
				$response = [
					'message' => 'Маркер удален успешно.'
				];
			}
			else
			{
				$status = 500;
				$response = [
					'error' => true,
					'message' => 'Ошибка удаления.'
				];
			}
		}
		return response()->json($response, $status);
	}
}
