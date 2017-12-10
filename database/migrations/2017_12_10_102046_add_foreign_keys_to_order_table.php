<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order', function(Blueprint $table)
		{
			$table->foreign('customer_id', 'Order_Customer')->references('id')->on('user')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('discount_id', 'Order_Discount')->references('id')->on('discount')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('location_id', 'Order_Location')->references('id')->on('location')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order', function(Blueprint $table)
		{
			$table->dropForeign('Order_Customer');
			$table->dropForeign('Order_Discount');
			$table->dropForeign('Order_Location');
		});
	}

}
