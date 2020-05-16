<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('role', 30)->default('user');
			$table->string('name', 140);
			$table->string('image', 140)->default('/img/avatar.png');
			$table->text('token')->nullable();
			$table->string('phone', 15)->unique();
			$table->string('email')->unique()->nullable();
			$table->string('password');
			$table->timestamp('email_verified_at')->nullable();
			
			$table->rememberToken();
			$table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
