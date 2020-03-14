<?php

namespace App\Http\Controllers\Api;

use App\{Marker, OrderedMarker};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class YandexCallbackController extends Controller
{
	public function webhook(Request $request)
	{
		Log::info('WEBHOOK: ', $request->all());
		
		if (!empty($request->event))
		{
			$payment = $request->object;
			$findOrder = OrderedMarker::where('uniqid', '=', $payment['id'])->first();
			
			if ($findOrder)
			{
				if ($request->event == 'payment.succeeded')
				{
					$findOrder->amount = $payment['amount']['value'];
					$findOrder->status = 'success';
					$findOrder->save();
					
					Log::info('Заказ успешно оплачен: ', $request->all());
				}
				elseif ($request->event == 'payment.canceled')
				{
					$findOrder->amount = $payment['amount']['value'];
					$findOrder->status = 'canceled';
					$findOrder->save();
					
					Log::info('Заказ отменен: ', $request->all());
				}
				else
				{
					$findOrder->status = 'canceled';
					$findOrder->save();
					
					Log::error('Оплата не прошла успешно: ', $request->all());
				}
			}
			else
			{
				Log::error('Заказ не найден: ', $request->all());
			}
			
		}
		else
		{
			Log::error('запрос не от яндекса: ', $request->all());
		}
		return 'OK';
	}
}
