<?php

namespace Tests\Feature;

use App\Shipping;
use Tests\TestCore\BaseTestCase;

class ShippingTest extends BaseTestCase
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

    public function testGetShippingList()
    {
        $data = Shipping::orderBy('lower_bound')->get();
        $response = $this->get('shipping/manage');
        $response->assertViewHas([
            'data' => $data,
        ]);
    }

    public function testAddShipping()
    {
        $lastShipping = Shipping::orderBy('id', 'DESC')->first();
        $lower = random_int(1, 5000);
        $upper = random_int(1, 5000) + $lower;
        $price = random_int(0, 500);
        $response = $this->post('shipping/manage', [
            'lower_bound' => $lower,
            'upper_bound' => $upper,
            'price' => $price,
        ]);

        $response->assertSessionHas('log', '建立成功');

        $newShipping = Shipping::where('id', '>', $lastShipping->id)->first();
        $this->assertEquals($lower, $newShipping->lower_bound);
        $this->assertEquals($upper, $newShipping->upper_bound);
        $this->assertEquals($price, $newShipping->price);
    }

    public function testDeleteShipping()
    {
        $this->testAddShipping();
        $lastShipping = Shipping::orderBy('id', 'DESC')->first();
        $this->delete('shipping/manage/delete', [
            'id' => $lastShipping->id,
        ])->assertSuccessful();

        $this->assertTrue(Shipping::where('id', $lastShipping->id)->doesntExist());
    }
}
