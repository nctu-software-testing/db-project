<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCore\BaseTestCase;

class HomeTest extends BaseTestCase
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

    public function testHomepage()
    {
        $products = Product::getHotProducts();
        $response = $this->get('/');
        $response->assertViewHas([
            'category',
            'products' => $products,
        ]);
    }

    public function testVerifyMessageNotShownWhenVerified()
    {
        $this->withUser('admin');
        $this->assertEquals(1, session('user.enable'));

        $response = $this->get('/');
        $response->assertDontSeeText('你未通過驗證，快去驗證');
    }

    public function testVerifyMessageShownWhenNotVerified()
    {
        $this->withUser('acco001');
        $this->assertEquals(0, session('user.enable'));

        $response = $this->get('/');
        $response->assertSeeText('你未通過驗證，快去驗證');
    }

    public function testNonLoggedInNotShowVerifyMessage()
    {
        $this->withUser(null);
        $response = $this->get('/');
        $response->assertDontSeeText('你未通過驗證，快去驗證');
    }
}
