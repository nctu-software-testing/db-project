<?php


namespace Tests\Feature\HW3;


use App\Order;
use App\Product;
use Carbon\Carbon;
use Tests\TestCore\BaseTestCase;

class ShoppingCartCCTest extends BaseTestCase
{

    protected function setUp()
    {
        parent::setUp();
        $this->withUser('c');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testDeleteShoppingCartCaseN()
    {
        $response = $this->delete('shopping-cart', []);
    }

    public function testGetShoppingCartCaseN()
    {
        $response = $this->get('shopping-cart', []);
    }

    public function testBuyProductCaseN()
    {
        $response = $this->post('buy', []);
    }

    public function testChangeAmountCaseN()
    {
        $response = $this->post('changeAmount', []);
    }

    public function testCheckOutCase1()
    {
        session()->put('discount', ['code' => '']);
        $response = $this->post('checkout', [
            'location' => null,
        ]);

        $response->assertRedirect();
        $this->assertEquals('參數錯誤', session('log'));
    }

    public function testCheckOutCase2()
    {
        Carbon::setTestNow('2020-06-05 00:00:00');
        $lastOrderId = Order::orderBy('id', 'DESC')->first()->id;

        // shopping-cart page need visit first
        $this->post('shopping-cart')->assertSuccessful();

        $response = $this->post('checkout', [
            'location' => 22,
        ]);

        $response->assertRedirect();
        $this->assertEquals('成功', session('log'));
        $this->assertNull(session('shoppingcart'));
        $this->assertNull(session('discount'));
        $newOrder = Order::where('id', '>', $lastOrderId)->first();
        $this->assertNotNull($newOrder);

        $this->assertEquals(80, $newOrder->shipping_cost);
        $this->assertEquals(0, $newOrder->original_cost);
        $this->assertEquals(80 + 0, $newOrder->final_cost);
        $this->assertEquals(session('user.id'), $newOrder->customer_id);
        $this->assertNull($newOrder->discount_id);
        $this->assertEquals(Product::STATE_DRAFT, $newOrder->state);
        $this->assertEquals('2020-06-05 01:00:00', $newOrder->sent_time);
        $this->assertEquals('2020-06-05 06:00:00', $newOrder->arrival_time);
    }

    public function testCheckOutCase3()
    {
        Carbon::setTestNow('2020-06-05 00:00:00');
        $lastOrderId = Order::orderBy('id', 'DESC')->first()->id;

        $targetProduct = Product::find(36);
        $targetProduct->state = Product::STATE_RELEASE;
        $targetProduct->amount = 999;
        $targetProduct->save();

        // add item into cart
        $this->post('buy', [
            'id' => $targetProduct->id,
            'amount' => 1,
        ])->assertJson([
            'result' => 'OK',
            'success' => true,
        ]);

        // shopping-cart page need visit first
        $this->post('shopping-cart')->assertSuccessful();

        $response = $this->post('checkout', [
            'location' => 22,
        ]);

        $response->assertRedirect();
        $this->assertEquals('成功', session('log'));
        $this->assertNull(session('shoppingcart'));
        $this->assertNull(session('discount'));
        $newOrder = Order::where('id', '>', $lastOrderId)->first();
        $this->assertNotNull($newOrder);

        $this->assertEquals(80, $newOrder->shipping_cost);
        $this->assertEquals($targetProduct->price, $newOrder->original_cost);
        $this->assertEquals(80 + $targetProduct->price, $newOrder->final_cost);
        $this->assertEquals(session('user.id'), $newOrder->customer_id);
        $this->assertNull($newOrder->discount_id);
        $this->assertEquals(Product::STATE_DRAFT, $newOrder->state);
        $this->assertEquals('2020-06-05 01:00:00', $newOrder->sent_time);
        $this->assertEquals('2020-06-05 06:00:00', $newOrder->arrival_time);
    }

    public function testRemoveProductFromShoppingcartCaseN()
    {
        $response = $this->post('removeProductFromShoppingcart', []);
    }

    public function testPostShoppingCartCaseN()
    {
        $response = $this->post('shopping-cart', []);
    }

    public function testPostSetDiscountCaseN()
    {
        $response = $this->post('shopping-cart/discount', []);
    }
}
