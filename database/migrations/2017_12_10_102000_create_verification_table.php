<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVerificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('verification', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('Verification_User');
			$table->string('front_picture');
			$table->string('back_picture');
			$table->dateTime('upload_datetime')->nullable()->default(null);
			$table->string('verify_result', 8)->nullable()->default('未驗證');
			$table->dateTime('datetime')->nullable()->default(null);
			$table->string('description', 100)->nullable()->default(null);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('verification');
	}

}
