<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductPictureTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_picture', function(Blueprint $table)
		{
			$table->foreign('product_id', 'Pic_Pro')->references('id')->on('on_product')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_picture', function(Blueprint $table)
		{
			$table->dropForeign('Pic_Pro');
		});
	}

}
