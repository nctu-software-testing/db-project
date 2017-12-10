<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOnProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('on_product', function(Blueprint $table)
		{
			$table->foreign('user_id', 'Product_Businessman')->references('id')->on('user')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('category_id', 'Product_Category')->references('id')->on('category')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('on_product', function(Blueprint $table)
		{
			$table->dropForeign('Product_Businessman');
			$table->dropForeign('Product_Category');
		});
	}

}
