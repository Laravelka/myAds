<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('markers', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->enum('type', [
				'transport', 
				'stops', 
				'cafes', 
				'gyms', 
				'billboard'
			]);
			$table->string('image')->default('/img/billboard.png');
			$table->enum('type_price', [
				'normal', 
				'special'
			]);
			$table->decimal('latitude', 8, 6);
			$table->decimal('longitude', 8, 6);
			$table->mediumText('address');
			$table->string('size_billboard')->nullable();
			$table->bigInteger('price_year')->default(0);
			$table->bigInteger('price_month')->default(0);
			
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('markers');
	}
}
