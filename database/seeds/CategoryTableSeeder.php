<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = explode("\n", trim("女生衣著
男生衣著
美妝保健
手機平板與周邊
嬰幼童與母親
3C相關
居家生活
家電影音
女生配件
男生包包與配件
女鞋
男鞋
女生包包
戶外與運動用品
美食、伴手禮
汽機車零件百貨
寵物
娛樂、收藏
服務、票券
遊戲王
代買代購
其他類別"));

        $i = 0;
        foreach ($type as &$t) {
            $t = trim($t);
            if (!DB::table('category')->where('product_type', $t)->exists()) {
                DB::table('category')->insert([
                    'product_type' => $t,
                    'image_index' => $i++,
                ]);
            }
        }
    }
}
