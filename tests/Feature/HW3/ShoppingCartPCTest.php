<?php


namespace Tests\Feature\HW3;

use App\Order;
use App\Product;
use Carbon\Carbon;
use Tests\TestCore\BaseTestCase;

class ShoppingCartPCTest extends BaseTestCase
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

    public function testDeleteShoppingCartCase()
    {
        $response = $this->delete('shopping-cart', []);
        $response->assertJson([
            'result' => 'OK',
            'success' => true,
        ]);
    }

    public function testGetShoppingCartCaseN()
    {
        $response = $this->get('shopping-cart', []);
        $this->assertTrue(true);
    }

    public function testBuyProductCase1()
    {
        // cannot find product
        
        $this->post('buy', [
            'id' => 0,
            'amount' => 1,
        ])->assertJson([
            'result' => 'WTF',
            'success' => false,
        ]);        
    }

    public function testBuyProductCase2()
    {
        // out of stock

        Carbon::setTestNow('2020-06-13 00:00:00');
        $targetProduct = Product::find(71);
        $targetProduct->state = Product::STATE_RELEASE;
        $targetProduct->amount = 0;
        $targetProduct->save();
        
        $this->post('buy', [
            'id' => $targetProduct->id,
            'amount' => 1,
        ])->assertJson([
            'result' => '沒有庫存了',
            'success' => false,
        ]);        
    }

    public function testBuyProductCase3()
    {
        // not array

        Carbon::setTestNow('2020-06-13 00:00:00');
        $targetProduct = Product::find(71);
        $targetProduct->state = Product::STATE_RELEASE;
        $targetProduct->amount = 100;
        $targetProduct->save();
        
        session(['shoppingcart' => 'tE5Tn0tArR@y']);

        $this->post('buy', [
            'id' => $targetProduct->id,
            'amount' => 1,
        ])->assertJson([
            'result' => 'OK',
            'success' => true,
        ]);        
    }

    public function testChangeAmountCaseN()
    {
        
    }
}
