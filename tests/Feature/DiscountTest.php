<?php

namespace Tests\Feature;

use App\Category;
use App\Catlog;
use App\Discount;
use Carbon\Carbon;
use Tests\TestCore\BaseTestCase;

class DiscountTest extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->withUser('admin');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGetDiscountList()
    {
        $response = $this->get('discount/manage');
        $response->assertViewHas([
            'data'
        ]);
    }

    public function testCreateDiscountA()
    {
        $lastDiscountId = Discount::orderBy('id', 'DESC')->first()->id;
        $type = 'A';
        $name = str_random();
        $value = '0.15';
        $startTime = Carbon::now();
        $endTime = Carbon::now()->addDays(10);
        $response = $this->post('discount/manage/create', [
            'type' => $type,
            'name' => $name,
            'value' => $value,
            'start_date' => $startTime,
            'end_date' => $endTime,
        ]);

        $response->assertSessionHas('log', '建立成功');
        $newDiscount = Discount::where('id', '>', $lastDiscountId)->first();
        $this->assertEquals($type, $newDiscount->type);
        $this->assertEquals($name, $newDiscount->name);
        $this->assertEquals($value, $newDiscount->value);
        $this->assertEquals($startTime, $newDiscount->start_discount_time);
        $this->assertEquals($endTime, $newDiscount->end_discount_time);
    }

    public function testCreateDiscountB()
    {
        $lastDiscountId = Discount::orderBy('id', 'DESC')->first()->id;
        $type = 'B';
        $name = str_random();
        $value = '20';
        $startTime = Carbon::now();
        $endTime = Carbon::now()->addDays(10);
        $response = $this->post('discount/manage/create', [
            'type' => $type,
            'name' => $name,
            'value' => $value,
            'start_date' => $startTime,
            'end_date' => $endTime,
        ]);

        $response->assertSessionHas('log', '建立成功');
        $newDiscount = Discount::where('id', '>', $lastDiscountId)->first();
        $this->assertEquals($type, $newDiscount->type);
        $this->assertEquals($name, $newDiscount->name);
        $this->assertEquals($value, $newDiscount->value);
        $this->assertEquals($startTime, $newDiscount->start_discount_time);
        $this->assertEquals($endTime, $newDiscount->end_discount_time);
    }

    public function testCreateDiscountC()
    {
        $category = Category::first();
        $lastDiscountId = Discount::orderBy('id', 'DESC')->first()->id;
        $type = 'C';
        $name = str_random();
        $value = '0.6';
        $startTime = Carbon::now();
        $endTime = Carbon::now()->addDays(10);
        $response = $this->post('discount/manage/create', [
            'type' => $type,
            'name' => $name,
            'value' => $value,
            'start_date' => $startTime,
            'end_date' => $endTime,
            'category' => $category->id,
        ]);

        $response->assertSessionHas('log', '建立成功');
        $newDiscount = Discount::where('id', '>', $lastDiscountId)->first();
        $this->assertEquals($type, $newDiscount->type);
        $this->assertEquals($name, $newDiscount->name);
        $this->assertEquals($value, $newDiscount->value);
        $this->assertEquals($startTime, $newDiscount->start_discount_time);
        $this->assertEquals($endTime, $newDiscount->end_discount_time);

        $catlog = Catlog::where('discount_id', $newDiscount->id)->first();
        $this->assertEquals($category->id, $catlog->category_id);
    }

    public function testDisableDiscount()
    {
        $now = Carbon::parse('2020-06-22 03:00:00');
        Carbon::setTestNow($now);

        $newDiscount = new Discount();
        $newDiscount->name = 'test discount';
        $newDiscount->start_discount_time = (clone $now)->addDays(-1);
        $newDiscount->end_discount_time = (clone $now)->addDays(5);
        $newDiscount->value = '0.2';
        $newDiscount->type = 'A';
        $newDiscount->save();

        $finalCost = 10000;
        session()->put('final', $finalCost);

        // apply discount
        do {
            $response = $this->post('shopping-cart/discount', [
                'code' => Discount::encrypt($newDiscount->id),
            ]);

            $data = $response->json()['result'];
            $this->assertArraySubset([
                'message' => '套用優惠成功',
            ], $data);
            $this->assertEquals($finalCost * (1 - $newDiscount->value), $data['final_cost']);
            $response->assertSessionHas('discount');
        } while (false);

        $disableResponse = $this->post('discount/manage/disable', [
            'id' => $newDiscount->id
        ]);
        $disableResponse->assertJson([
            'result' => 'OK',
            'success' => true,
        ]);

        Carbon::setTestNow((clone $now)->addSeconds(1));

        // try apply disabled discount
        do {
            $response = $this->post('shopping-cart/discount', [
                'code' => Discount::encrypt($newDiscount->id),
            ]);

            $data = $response->json()['result'];
            $this->assertArraySubset([
                'message' => '找不到符合條件的優惠',
            ], $data);
            $response->assertSessionMissing('discount');
        } while (false);
    }
}
