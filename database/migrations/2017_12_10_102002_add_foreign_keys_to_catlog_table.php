<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCatlogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('catlog', function(Blueprint $table)
		{
			$table->foreign('category_id', 'CatLog_Category')->references('id')->on('category')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('discount_id', 'CatLog_Discount')->references('id')->on('discount')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('catlog', function(Blueprint $table)
		{
			$table->dropForeign('CatLog_Category');
			$table->dropForeign('CatLog_Discount');
		});
	}

}
