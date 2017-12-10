<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShippingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipping', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('lower_bound')->comment('總價下限');
			$table->integer('upper_bound')->comment('總價上限');
			$table->integer('price')->comment('運費');
			$table->index(['lower_bound','upper_bound'], 'ship_bound_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shipping');
	}

}
