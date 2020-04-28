<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table
                ->integer('original_cost')
                ->default(0)
                ->comment('折扣前的原始價錢')
                ->after('arrival_time');
            $table
                ->integer('shipping_cost')
                ->default(0)
                ->after('original_cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn(['shipping_cost', 'original_cost']);
        });
    }
}
