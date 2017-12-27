<?php

use Illuminate\Database\Seeder;

class ShippingTableSeeder extends Seeder
{
    protected $table = 'shipping';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->table)->truncate();
        $data = [
            ['lower_bound' => 0, 'upper_bound' => 100, 'price' => 80],
            ['lower_bound' => 101, 'upper_bound' => 500, 'price' => 60],
            ['lower_bound' => 501, 'upper_bound' => 1000, 'price' => 40],
            ['lower_bound' => 1001, 'upper_bound' => 2147483647, 'price' => 0],
        ];
        foreach ($data as $d) {
            DB::table($this->table)->insert($d);
        }
    }
}
