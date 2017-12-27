<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_product', function(Blueprint $table)
		{
			$table->foreign('order_id', 'OP_Order')->references('id')->on('order')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('product_id', 'OP_Poduct')->references('id')->on('on_product')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_product', function(Blueprint $table)
		{
			$table->dropForeign('OP_Order');
			$table->dropForeign('OP_Poduct');
		});
	}

}
