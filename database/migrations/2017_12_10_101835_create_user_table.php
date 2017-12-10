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
			$table->integer('id', true);
			$table->string('account', 50)->unique('account');
			$table->string('password', 60);
			$table->string('sn', 50)->nullable()->default('NULL');
			$table->char('role', 1);
			$table->text('name', 65535);
			$table->dateTime('sign_up_datetime')->nullable()->default('current_timestamp()');
			$table->date('birthday');
			$table->text('gender', 65535);
			$table->text('email', 65535);
			$table->boolean('enable')->default(0);
			$table->string('avatar', 12)->default('\'\'')->comment('Imgur ID');
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
