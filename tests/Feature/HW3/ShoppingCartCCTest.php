<?php


namespace Tests\Feature\HW3;


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

    public function testCheckOutCaseN()
    {
        $response = $this->post('checkout', []);
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
