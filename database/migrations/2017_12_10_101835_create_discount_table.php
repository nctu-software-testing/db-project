<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discount', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 100);
			$table->dateTime('start_discount_time')->nullable()->default('NULL');
			$table->dateTime('end_discount_time')->nullable()->default('NULL');
			$table->char('type', 1);
			$table->float('discount_percent', 11);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('discount');
	}

}
