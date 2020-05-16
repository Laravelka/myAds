<?php

namespace App\Http\Controllers\Api;

use DB;
use Validator;
use Carbon\Carbon;
use YandexCheckout\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Marker, User, OrderedMarker};
use Illuminate\Support\Facades\{Redis, Auth, Log};

class OrdersController extends Controller
{
	protected $client;
	
	public function create(Request $request, Client $client)
	{
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
			'marker_id' => ['required', 'numeric'],
			'end_date' => ['required', 'date', 'after:start_date'],
			'start_date' => ['required', 'date', 'after:yesterday'],
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
			$marker = DB::table('markers')
						->where('markers.id', $inputs['marker_id'])
						->leftJoin('ordered_markers', 'markers.id', '=', 'ordered_markers.marker_id')
						->select(
							'markers.*', 
							'ordered_markers.end_date', 
							'ordered_markers.start_date', 
							'ordered_markers.user_id', 
							'ordered_markers.status',
							'ordered_markers.amount'
						)
						->get();
			
			if ($marker->isEmpty())
			{
				$status = 400;
				$response = [
					'error' => true,
					'message' => 'Маркер не найден.'
				];
			}
			elseif (!empty($marker[0]->user_id))
			{
				$status = 400;
				$response = [
					'error' => true,
					'message' => 'Маркер пока занят.'
				];
			}
			else
			{
				$endDate   = Carbon::create($inputs['end_date']);
				$startDate = Carbon::create($inputs['start_date']);
				
				$days = $startDate->diffInDays($endDate);
				$amount = ($marker[0]->price_month / 30) * $days;
				$uniqid = uniqid('', true);
				
				$order = Auth::user()->orderedMarker()->create(array_merge($inputs, [
					'status' => 'pending',
					'uniqid' => $uniqid,
					'amount' => $amount,
				]));
				
				$paymentResponse = $client->createPayment(
					[
						'amount' => [
							'value' => $amount,
							'currency' => 'RUB',
						],
						'confirmation' => [
							'type' => 'redirect',
							'return_url' => 'https://shopreklama.ru/',
						],
							'capture' => true,
							'description' => 'Заказ №'.$order->id,
					],
					$uniqid
				);
				
				Log::debug('PAYMENT', (array) $paymentResponse);
				
				if (!empty($paymentResponse['confirmation']))
				{
					$order->uniqid = $paymentResponse['id'];
					$order->save();
					
					$status = 200;
					$response = [
						'message'  => 'Маркер заказан успешно.',
						'redirect' => $paymentResponse['confirmation']['confirmation_url']
					];
				}
				else
				{
					$status = 500;
					$response = [
						'error' => true,
						'response' => $paymentResponse,
						'messаge' => 'Ошибка на стороне Yandex.'
					];
				}
			}
		}
		return response()->json($response, $status);
	}
	
	public function getById(Request $request)
	{
		$id = $request->id ?? 0;
		$order = DB::table('markers')
					->where('ordered_markers.id', $id)
					->leftJoin('ordered_markers', 'markers.id', '=', 'ordered_markers.marker_id')
					->select(
						'markers.*',
						'ordered_markers.end_date',
						'ordered_markers.start_date',
						'ordered_markers.user_id',
						'ordered_markers.status',
						'ordered_markers.amount'
					)
					->get();
		
		if (!$order)
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'Заказ не найден.'
			];
		}
		else
		{
			$status = 200;
			$response = [
				'order' => $order
			];
		}
		return response()->json($response, $status);
	}
	
	public function getAll(Request $request)
	{
		$orders = DB::table('markers')
						->where('ordered_markers.user_id', Auth::user()->id)
						->where('ordered_markers.status', '<>', 'canceled')
						->leftJoin('ordered_markers', 'markers.id', '=', 'ordered_markers.marker_id')
						->select(
							'markers.*',
							'ordered_markers.end_date',
							'ordered_markers.start_date',
							'ordered_markers.user_id',
							'ordered_markers.status',
							'ordered_markers.amount',
							DB::raw('ordered_markers.id as order_id')
						)
						->get();
		
		if ($orders->isEmpty())
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'История пуста.'
			];
		}
		else
		{
			$status = 200;
			$response = [
				'orders' => $orders
			];
		}
		return response()->json($response, $status);
	}
	
	public function cancel(Request $request)
	{
		$id = $request->id ?? 0;
		$user = Auth::user();
		$order = $user->orderedMarker()->find($id)->first();
		
		if (!$order)
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'Заказ не найден.'
			];
		}
		elseif ($order->status == 'canceled')
		{
			$status = 400;
			$response = [
				'error' => true,
				'message' => 'Заказ уже отменен.'
			];
		}
		else
		{
			$order->status = 'canceled';
			$order->save();
			
			$status = 200;
			$response = [
				'message' => 'Заказ успешно отменен.'
			];
		}
		return response()->json($response, $status);
	}
}
