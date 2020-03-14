<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_markers', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('uniqid')->nullable();
			$table->timestamp('end_date')->nullable();
			$table->timestamp('start_date')->nullable();
			$table->enum('status', ['success', 'canceled', 'pending']);
            $table->timestamps();
        });
        
        Schema::table('ordered_markers', function(Blueprint $table) {
			$table->bigInteger('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('marker_id')->unsigned();
			$table->foreign('marker_id')->references('id')->on('markers');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordered_markers');
    }
}
