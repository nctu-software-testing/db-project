<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePrivateMessageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('private_message', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('receive_id')->index('PM_User');
			$table->boolean('is_read')->default(0);
			$table->string('title', 100);
			$table->text('content', 65535);
			$table->dateTime('send_datetime')->nullable()->default(null);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('private_message');
	}

}
