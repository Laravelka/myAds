<?php

namespace App\Providers;

use YandexCheckout\Client;
use Illuminate\Support\ServiceProvider;

class YandexKassaServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(Client::class, function() {
			$client = new Client();
			$client->setAuth(config('yandex.shopId'), config('yandex.secretKey'));
			return $client;
		});
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
}
