<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOnProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('on_product', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('product_name');
			$table->text('product_information', 65535);
			$table->dateTime('start_date')->nullable()->default(null);
			$table->dateTime('end_date')->nullable()->default(null);
			$table->integer('price');
			$table->integer('state');
			$table->integer('category_id')->index('Product_Category');
			$table->integer('user_id')->index('Product_Businessman');
			$table->integer('amount')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('on_product');
	}

}
