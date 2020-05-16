<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChatsController extends Controller
{
	public function getAll(Request $request)
	{
		return view('admin.chats', [
			'chats' => [
				[
					'user_id' => 1, 'name' => 'User Name', 'image' => '/storage/avatars/2_avatar1584197940.jpg', 'last_message' => [
					'id' => 2, 'readed' => false, 'text' => 'Примерный текст', 'created_at' => Carbon::now()
					]
				]
			]
		]);
	}
}
