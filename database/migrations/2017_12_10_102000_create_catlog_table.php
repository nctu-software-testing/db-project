<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCatlogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('catlog', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('discount_id')->index('CatLog_Discount');
			$table->integer('category_id')->index('CatLog_Category');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('catlog');
	}

}
