<?php

namespace App\Http\Controllers\Api;

use DB;
use Validator;
use App\{User, Marker, OrderedMarker};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
