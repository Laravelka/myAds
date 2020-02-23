<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
	public function update(Request $request)
	{
		$token = Str::random(80);
		$request->user()->forceFill([
			'token' => hash('sha256', $token),
		])->save();
		
		return ['token' => $token];
	}
}
