<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
	public function getAll(Request $request)
	{
		$users = User::get();

		if ($users->isEmpty())
		{
			$response = [
				'error' => true,
				'message' => 'Пока пусто.'
			];
		}
		else
		{	
			$response = [
				'title' => 'Пользователи',
				'users' => $users
			];
		}
		return view('admin.users', $response);
	}
	
	public function getById($id)
	{
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
				'user' => $user
			];
		}
		return response()->json($response, $status);
	}
}
