<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('location_id')->index('Order_Location');
			$table->integer('customer_id')->index('Order_Customer');
			$table->integer('state');
			$table->dateTime('order_time')->nullable()->default('current_timestamp()');
			$table->dateTime('sent_time')->nullable()->default(null);
			$table->dateTime('arrival_time')->nullable()->default(null);
			$table->integer('final_cost');
			$table->integer('discount_id')->nullable()->default(null)->index('Order_Discount');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order');
	}

}
