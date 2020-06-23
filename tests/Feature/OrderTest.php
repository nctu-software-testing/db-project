<?php

namespace Tests\Feature;

use App\Order;
use Tests\TestCore\BaseTestCase;

class OrderTest extends BaseTestCase
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

    public function testGetMyOrders()
    {
        $response = $this->get('order');
        $response->assertViewIs('order');
        $userId = session('user.id');
        $this->assertTrue(
            collect($response->getOriginalContent()->data->getCollection())
                ->every('customer_id', '=', $userId)
        );
    }

    public function testGetMyOrderDetails()
    {
        $orderId = Order::where('customer_id', session('user.id'))->first()->id;

        $response = $this->get('orderDetail/' . $orderId);
        $response->assertViewIs('orderDetail');
        $response->assertViewHas([
            'data',
            'location',
            'order',
            'originalcost',
            'discountAmount',
        ]);
    }

    public function testGetNotExistsOrderDetails()
    {
        $orderId = rand(999, 9999);
        $response = $this->get('orderDetail/' . $orderId);
        $response->assertStatus(404);
    }

    public function testGetOthersOrderDetails()
    {
        $orderId = 2;
        $response = $this->get('orderDetail/' . $orderId);
        $response->assertStatus(404);
    }

    public function testGetShippingList()
    {
        $this->withUser('b2');
        $response = $this->get('order/shipping-status');
        $response->assertViewIs('shipping-status');
    }
}
