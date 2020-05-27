<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('account', 50);
			$table->string('password', 60);
			$table->string('sn', 50)->nullable()->default(null);
			$table->char('role', 1);
			$table->string('name');
			$table->dateTime('sign_up_datetime')->nullable()->default(\DB::raw('current_timestamp'));
			$table->date('birthday');
			$table->string('gender', 10);
			$table->string('email');
			$table->boolean('enable')->default(0);
			$table->string('avatar', 12)->default('')->comment('Imgur ID');

			$table->unique('account');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user');
	}

}
