<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Validator;
use App\Marker;
use Illuminate\Support\Facades\{Storage, Redis};

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
			$status = 400;
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
			
			$status = 200;
			$response = [
				'markers' => $markers
			];
		}
		return response()->json($response, $status);
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
			$marker->image = '/storage'.$marker->image;
			
			$status = 200;
			$response = [
				'marker' => $marker
			];
		}
		return response()->json($response, $status);
	}
	
	public function create(Request $request)
	{
		$user = $request->user();
		$validator = Validator::make($request->all(), [
			'address' => 'required|string|min:10|unique:markers',
			'image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
			'latitude' => 'required|regex:/^\d*(\.\d{1,8})?$/',
			'longitude' => 'required|regex:/^\d*(\.\d{1,8})?$/',
			'price_year' => 'required|numeric|min:1',
			'price_month' => 'required|numeric|min:1',
			'type_price' => 'required|in:normal,special',
			'size_billboard' => 'required'
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
			$marker = new Marker();
			$marker->address = $request->address;
			$marker->latitude = $request->latitude;
			$marker->longitude = $request->longitude;
			$marker->price_year = $request->price_year;
			$marker->type_price = $request->type_price;
			$marker->price_month = $request->price_month;
			$marker->size_billboard = $request->size_billboard;
			
			if ($request->has('image'))
			{
				$name = $user->id.'_marker_'.time().'_'.$marker->id.'.'.$request->image->getClientOriginalExtension();
				$request->image->storeAs('markers', $name);
				$marker->image = '/markers/'.$name;
			}
			$marker->save();
			
			$status = 200;
			$response = [
				'marker' => $marker,
				'message' => 'Маркер успешно добавлен!'
			];
		}
		return response()->json($response, $status);
	}
	
	public function update(Request $request)
	{
		$user = $request->user();
		$validator = Validator::make($request->all(), [
			'address' => 'required|string|min:10',
			'image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
			'latitude' => 'required|regex:/^\d*(\.\d{1,8})?$/',
			'longitude' => 'required|regex:/^\d*(\.\d{1,8})?$/',
			'price_year' => 'required|numeric|min:1',
			'price_month' => 'required|numeric|min:1',
			'type_price' => 'required|in:normal,special',
			'size_billboard' => 'required'
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
			$marker->address = $request->address;
			$marker->latitude = $request->latitude;
			$marker->longitude = $request->longitude;
			$marker->price_year = $request->price_year;
			$marker->type_price = $request->type_price;
			$marker->price_month = $request->price_month;
			$marker->size_billboard = $request->size_billboard;
			
			if ($request->has('image'))
			{
				$name = $user->id.'_marker_'.time().'_'.$marker->id.'.'.$request->image->getClientOriginalExtension();
				$request->image->storeAs('markers', $name);
				$marker->image = '/markers/'.$name;
			}
			$marker->save();
			
			$status = 200;
			$response = [
				'message' => 'Маркер успешно отредактипрован'
			];
		}
		return response()->json($response, $status);
	}
	
	public function delete($id)
	{
		$marker = Marker::find($id ?? 0);
		
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
			$boolean = (
				$marker->image == '/markers/billboard.png' ? true : Storage::disk('public')->delete($marker->image)
			);
			
			if ($boolean)
			{
				$marker->orderedMarker()->delete();
				$marker->delete();
				
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
