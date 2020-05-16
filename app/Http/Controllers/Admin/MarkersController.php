<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Validator;
use App\Marker;
use Illuminate\Support\Facades\Redis;

class MarkersController extends Controller
{
	public function getAll(Request $request)
	{
		$markers = DB::table('markers')
						->leftJoin('ordered_markers', 'markers.id', '=', 'ordered_markers.marker_id')
						->select('markers.*', 'ordered_markers.end_date', 'ordered_markers.start_date', 'ordered_markers.status')
						->get();

		if ($markers->isEmpty())
		{
			$response = [
				'error' => true,
				'message' => 'Пока пусто.'
			];
		}
		else
		{
			$markers = array_map(function($item) {
				$item->image = '/storage'.$item->image;
				
				return $item;
			}, $markers->toArray());
			
			$response = [
				'title' => 'Маркеры',
				'markers' => $markers
			];
		}
		return view('admin.markers', $response);
	}
	
	public function getById(Request $request)
	{
		$id = $request->id ?? 0;
		$marker = DB::table('markers')
						->where('markers.id', $id)
						->leftJoin('ordered_markers', 'markers.id', '=', 'ordered_markers.marker_id')
						->select('markers.*', 'ordered_markers.end_date', 'ordered_markers.start_date', 'ordered_markers.user_id', 'ordered_markers.status')
						->first();
		
		if (!$marker)
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'Маркер не найден.'
			];
		}
		else
		{
			$status = 200;
			$response = [
				'marker' => $marker
			];
		}
		return response()->json($response, $status);
	}
	
	public function create(Request $request)
	{
		$id = $request->id ?? 0;
		$marker = DB::table('markers')
						->where('markers.id', $id)
						->leftJoin('ordered_markers', 'markers.id', '=', 'ordered_markers.marker_id')
						->select('markers.*', 'ordered_markers.end_date', 'ordered_markers.start_date', 'ordered_markers.user_id', 'ordered_markers.status')
						->first();
		
		if (!$marker)
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'Маркер не найден.'
			];
		}
		else
		{
			$status = 200;
			$response = [
				'marker' => $marker
			];
		}
		return response()->json($response, $status);
	}
	
	public function update(Request $request)
	{
		$user = $request->user();
		$validator = Validator::make($request->all(), [
			'address' => 'required|string|min:10',
			'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
			'latitude' => 'required|regex:/^\d*(\.\d{1,2})?$/',
			'longitude' => 'required|regex:/^\d*(\.\d{1,2})?$/',
			'price_year' => 'required|numeric|min:1',
			'price_month' => 'required|numeric|min:1',
			'type_price' => 'required|in:normal,special'
		]);
		$marker = Marker::find($request->id ?? 0);
		
		if (!$marker)
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'Маркер не найден.'
			];
		}
		elseif ($validator->fails())
		{
			$status = 400;
			$response = [
				'error' => true, 
				'messages' => $validator->errors()
			];
		}
		else
		{
			$name = $user->id.'_marker_'.$marker->id.'.'.$request->image->getClientOriginalExtension();
			$request->image->storeAs('markers', $name);
			
			$marker->save(array_merge($request->all(), ['image' => '/storage/markers/'.$name]));
			
			$status = 200;
			$response = [
				'message' => 'Маркер успешно отредактипрован'
			];
		}
		return response()->json($response, $status);
	}
}
