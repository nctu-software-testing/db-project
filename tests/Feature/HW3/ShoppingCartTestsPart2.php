<?php


namespace Tests\Feature\HW3;


use App\Discount;
use App\Order;
use App\Product;
use Carbon\Carbon;

trait ShoppingCartTestsPart2
{
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

    public function testRemoveProductFromShoppingcartCase1()
    {
        Carbon::setTestNow('2020-06-05 00:00:00');
        session()->put('shoppingcart', []);
        $response = $this->post('removeProductFromShoppingcart', [
            'id' => null,
        ]);

        $response->assertJson([
            'result' => 'OK',
            'success' => true,
        ]);

        $this->assertEquals([], session('shoppingcart'));
    }

    public function testRemoveProductFromShoppingcartCase2()
    {
        Carbon::setTestNow('2020-06-05 00:00:00');
        $targetProductIds = [83, 84, 85];
        $this->addProductIntoShoppingCart($targetProductIds);

        $response = $this->post('removeProductFromShoppingcart', [
            'id' => 83,
        ]);

        $response->assertJson([
            'result' => 'OK',
            'success' => true,
        ]);

        $productIds = array_map(function ($p) {
            return $p->product->id;
        }, session('shoppingcart'));

        $this->assertEquals([84, 85], $productIds);
    }

    public function testRemoveProductFromShoppingcartCase3()
    {
        Carbon::setTestNow('2020-06-05 00:00:00');
        $targetProductIds = [83, 84, 85];
        $this->addProductIntoShoppingCart($targetProductIds);

        $response = $this->post('removeProductFromShoppingcart', [
            'id' => 82,
        ]);

        $response->assertJson([
            'result' => 'OK',
            'success' => true,
        ]);

        $productIds = array_map(function ($p) {
            return $p->product->id;
        }, session('shoppingcart'));

        $this->assertEquals($targetProductIds, $productIds);
    }

    public function testPostShoppingCartCase1()
    {
        Carbon::setTestNow('2020-06-05 00:00:00');
        $targetProductIds = [83, 84, 85];
        $prices = [1490, 69, 3390];
        $this->addProductIntoShoppingCart($targetProductIds);

        // visit page to set variables
        $this->post('shopping-cart');

        // apply discount
        $discountCode = Discount::encrypt(1);
        $this->assertArraySubset([
            'message' => '套用優惠成功'
        ], $this->post('shopping-cart/discount', [
            'code' => $discountCode,
        ])->json()['result']);

        $response = $this->post('shopping-cart', []);

        $originalCost = array_reduce($prices, function ($a, $b) {
            return $a + $b;
        });

        $discountValue = round($originalCost * 0.8);
        $response->assertViewHas('final', $discountValue);
        $discountValue = $originalCost - $discountValue;
        $response->assertViewHas('discountAmount', $discountValue);
    }

    public function testPostShoppingCartCase2()
    {
        Carbon::setTestNow('2020-06-05 00:00:00');
        $targetProductIds = [83, 84, 85];
        $prices = [1490, 69, 3390];
        $this->addProductIntoShoppingCart($targetProductIds);

        $response = $this->post('shopping-cart', []);

        $originalCost = array_reduce($prices, function ($a, $b) {
            return $a + $b;
        });
        $response->assertViewHas('final', $originalCost);
        $response->assertViewHas('discountAmount', 0);
    }

    public function testPostSetDiscountCase1()
    {
        Carbon::setTestNow('1990-01-02 00:00:00');

        // visit page to set variables
        $this->post('shopping-cart');

        $response = $this->post('shopping-cart/discount', [
            'code' => Discount::encrypt(1),
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        $this->assertArraySubset([
            'message' => '找不到符合條件的優惠'
        ], $data['result']);
        $this->assertFalse($data['success']);
        $this->assertNull(session('discount'));
    }

    public function testPostSetDiscountCase2()
    {
        Carbon::setTestNow('2020-11-11 00:00:00');
        $targetProducts = [83, 84, 87];
        $prices = [1490, 69, 11900];
        $discountId = 1;

        $this->addProductIntoShoppingCart($targetProducts);

        // visit page to set variables
        $this->post('shopping-cart');

        $response = $this->post('shopping-cart/discount', [
            'code' => Discount::encrypt($discountId),
        ]);

        $originalCost = array_reduce($prices, function ($a, $b) {
            return $a + $b;
        });
        $discountAmount = round($originalCost * 0.2);

        $response->assertSuccessful();
        $data = $response->json();
        $this->assertArraySubset([
            'message' => '套用優惠成功',
            'type' => 'A',
            'discountAmount' => $discountAmount,
            'final_cost' => $originalCost - $discountAmount,
        ], $data['result']);

        $this->assertEquals([
            'final_price' => $originalCost - $discountAmount,
            'discountAmount' => $discountAmount,
            'code' => $discountId,
        ], session('discount'));
    }

    public function testPostSetDiscountCase3()
    {
        Carbon::setTestNow('2020-11-11 00:00:00');
        $targetProducts = [50];
        $prices = [2];
        $discountId = 6;

        $this->addProductIntoShoppingCart($targetProducts);

        // visit page to set variables
        $this->post('shopping-cart');

        $response = $this->post('shopping-cart/discount', [
            'code' => Discount::encrypt($discountId),
        ]);

        $originalCost = 2;
        $discountAmount = 20;

        $response->assertSuccessful();
        $data = $response->json();
        $this->assertArraySubset([
            'message' => '套用優惠成功',
            'type' => 'B',
            'discountAmount' => $discountAmount,
            'final_cost' => 0,
        ], $data['result']);

        $this->assertEquals([
            'final_price' => 0,
            'discountAmount' => $discountAmount,
            'code' => $discountId,
        ], session('discount'));
    }

    public function testPostSetDiscountCase4()
    {
        Carbon::setTestNow('2020-11-11 00:00:00');
        $targetProducts = [83, 84, 87];
        $prices = [1490, 69, 11900];
        $discountId = 5;

        $this->addProductIntoShoppingCart($targetProducts);

        // visit page to set variables
        $this->post('shopping-cart');

        $response = $this->post('shopping-cart/discount', [
            'code' => Discount::encrypt($discountId),
        ]);

        $originalCost = array_reduce($prices, function ($a, $b) {
            return $a + $b;
        });
        $discountAmount = round(11900 * 0.2);

        $response->assertSuccessful();
        $data = $response->json();
        $this->assertArraySubset([
            'message' => '套用優惠成功',
            'type' => 'C',
            'discountAmount' => $discountAmount,
            'final_cost' => $originalCost - $discountAmount,
        ], $data['result']);

        $this->assertEquals([
            'final_price' => $originalCost - $discountAmount,
            'discountAmount' => $discountAmount,
            'code' => $discountId,
        ], session('discount'));
    }

    public function testPostSetDiscountCase5()
    {
        Carbon::setTestNow('2020-11-11 00:00:00');
        $discountId = 5;

        // visit page to set variables
        $this->post('shopping-cart');

        $response = $this->post('shopping-cart/discount', [
            'code' => Discount::encrypt($discountId),
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        $this->assertArraySubset([
            'message' => '套用優惠成功',
            'type' => 'C',
            'discountAmount' => 0,
            'final_cost' => 0,
        ], $data['result']);

        $this->assertEquals([
            'final_price' => 0,
            'discountAmount' => 0,
            'code' => $discountId,
        ], session('discount'));
    }

    public function testPostSetDiscountCase6()
    {
        Carbon::setTestNow('2020-11-11 00:00:00');
        $targetProducts = [83, 84];
        $prices = [1490, 69];
        $discountId = 5;

        $this->addProductIntoShoppingCart($targetProducts);

        // visit page to set variables
        $this->post('shopping-cart');

        $response = $this->post('shopping-cart/discount', [
            'code' => Discount::encrypt($discountId),
        ]);

        $originalCost = array_reduce($prices, function ($a, $b) {
            return $a + $b;
        });

        $response->assertSuccessful();
        $data = $response->json();
        $this->assertArraySubset([
            'message' => '套用優惠成功',
            'type' => 'C',
            'discountAmount' => 0,
            'final_cost' => $originalCost,
        ], $data['result']);

        $this->assertEquals([
            'final_price' => $originalCost,
            'discountAmount' => 0,
            'code' => $discountId,
        ], session('discount'));
    }

    private function addProductIntoShoppingCart(array $ids)
    {
        Product::whereIn('id', $ids)->update([
            'state' => Product::STATE_RELEASE,
            'amount' => 999,
        ]);

        foreach ($ids as $id) {
            // add item into cart
            $this->post('buy', [
                'id' => $id,
                'amount' => 1,
            ])->assertJson([
                'result' => 'OK',
                'success' => true,
            ]);
        }

        $this->assertEquals(count($ids), count(session('shoppingcart')));
    }
}