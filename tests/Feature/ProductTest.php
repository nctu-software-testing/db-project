<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use Carbon\Carbon;
use Tests\TestCore\BaseTestCase;

class ProductTest extends BaseTestCase
{

    protected function setUp()
    {
        parent::setUp();
        Carbon::setTestNow('2020-06-01 01:02:03');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGetProducts()
    {
        $response = $this->get('/products');

        $this->assertCommonProductListAndGetPagination($response);
        $content = $response->getOriginalContent();

        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $pagination */
        $pagination = $content['data'];
        // only 40 items available
        $this->assertEquals(40, $pagination->total());
    }

    public function testExpiredItemsDoNotShownInList()
    {
        Carbon::setTestNow('2000-06-01 01:02:03');
        $response = $this->get('/products');
        $pagination = $this->assertCommonProductListAndGetPagination($response);

        // no any items available
        $this->assertEquals(0, $pagination->total());
    }

    public function testSearchProductsByName()
    {
        $response = $this->get('/products?' . http_build_query([
                'search[name]' => '紅茶',
            ]));

        $pagination = $this->assertCommonProductListAndGetPagination($response);

        // only 4 items available
        $this->assertEquals(4, $pagination->total());

        foreach ($pagination->items() as $item) {
            $this->assertContains('紅茶', $item->product_name);
        }
    }

    public function testSearchProductsByCategory()
    {
        $targetCategory = '家電影音';
        $categoryId = Category::where('product_type', $targetCategory)->first()->id;
        $response = $this->get('/products?' . http_build_query([
                'search[category]' => $categoryId,
            ]));

        $pagination = $this->assertCommonProductListAndGetPagination($response);

        // only 3 items available
        $this->assertEquals(3, $pagination->total());

        foreach ($pagination->items() as $item) {
            $this->assertEquals($targetCategory, $item->product_type);
        }
    }

    public function testSearchByPriceLowerBound()
    {
        $targetPrice = 1500;
        $response = $this->get('/products?' . http_build_query([
                'search[minPrice]' => $targetPrice,
            ]));

        $pagination = $this->assertCommonProductListAndGetPagination($response);

        // only 19 items available
        $this->assertEquals(19, $pagination->total());

        foreach ($pagination->items() as $item) {
            $this->assertGreaterThanOrEqual($targetPrice, $item->price);
        }
    }

    public function testSearchByPriceUpperBound()
    {
        $targetPrice = 500;
        $response = $this->get('/products?' . http_build_query([
                'search[maxPrice]' => $targetPrice,
            ]));

        $pagination = $this->assertCommonProductListAndGetPagination($response);

        // only 17 items available
        $this->assertEquals(17, $pagination->total());

        foreach ($pagination->items() as $item) {
            $this->assertLessThanOrEqual($targetPrice, $item->price);
        }
    }

    public function testSortProductByPriceAsc()
    {
        $response = $this->get('/products?' . http_build_query([
                'search[sort]' => 2,
            ]));

        $pagination = $this->assertCommonProductListAndGetPagination($response);

        $this->assertItemsOrderByField($pagination->items(), 'price', 'ASC');
    }

    public function testSortProductByPriceDesc()
    {
        $response = $this->get('/products?' . http_build_query([
                'search[sort]' => 3,
            ]));

        $pagination = $this->assertCommonProductListAndGetPagination($response);

        $this->assertItemsOrderByField($pagination->items(), 'price', 'DESC');
    }

    public function testSortProductByOrderCountDesc()
    {
        $response = $this->get('/products?' . http_build_query([
                'search[sort]' => 4,
            ]));

        $pagination = $this->assertCommonProductListAndGetPagination($response);

        $this->assertItemsOrderByField($pagination->items(), 'diffBuy', 'DESC');
    }

    public function testSortProductByStartDate()
    {
        $response = $this->get('/products?' . http_build_query([
                'search[sort]' => 5,
            ]));

        $pagination = $this->assertCommonProductListAndGetPagination($response);

        $this->assertItemsOrderByField($pagination->items(), 'start_date');
    }

    public function testNonBusinessCannotAccessManageProducts()
    {
        $this->withUser('c');
        $this->get('/products/manage')->assertRedirect('/');
    }

    public function testBusinessOnlyAccessManageHeSelfProducts()
    {
        $this->withUser('b1');
        $response = $this->get('/products/manage');
        $pagination = $this->assertCommonManageProductsAndGetPagination($response);

        $this->assertEquals(19, $pagination->total());

        $uid = session('user.id');
        foreach ($pagination->items() as $item) {
            $this->assertEquals($uid, $item->user_id);

            $this->assertNotEquals(Product::STATE_DELETED, $item->state);
        }
    }

    public function testSearchSelfManageProductsViaName()
    {
        $targetTitle = '外套';
        $this->withUser('b1');
        $response = $this->get('/products/manage?' . http_build_query([
                'title' => $targetTitle,
            ]));
        $pagination = $this->assertCommonManageProductsAndGetPagination($response);

        $this->assertEquals(4, $pagination->total());

        foreach ($pagination->items() as $item) {
            $this->assertContains($targetTitle, $item->product_name);
        }
    }

    public function testSearchSelfManageProductsViaCategory()
    {
        $targetCategory = '女生衣著';
        $categoryId = Category::where('product_type', $targetCategory)->first()->id;
        $this->withUser('b1');
        $response = $this->get('/products/manage?' . http_build_query([
                'category' => $categoryId,
            ]));
        $pagination = $this->assertCommonManageProductsAndGetPagination($response);

        $this->assertEquals(4, $pagination->total());

        foreach ($pagination->items() as $item) {
            $this->assertEquals($targetCategory, $item->product_type);
        }
    }

    public function testGetEditProductPage()
    {
        $productId = 55;
        $this->withUser('admin');
        $response = $this->get("/products/item/{$productId}/edit");
        $response->assertViewIs('management.product.modify');
        $data = $response->getOriginalContent();

        $this->assertEquals($productId, $data['id']);
        $this->assertEquals(0, $data['count']); // image count
        $product = $data['editdata'];
        $this->assertEquals($productId, $product->id);
    }

    public function testGetPublishProductEditPage()
    {
        $productId = 22;
        $this->withUser('admin');
        $response = $this->get("/products/item/{$productId}/edit");
        $this->assertTrue($response->isNotFound());
    }

    public function testGetOthersEditProductPage()
    {
        $productId = 1; // product #1's owner is uid = 1
        $this->withUser('b1');
        $response = $this->get("/products/item/{$productId}/edit");
        $this->assertTrue($response->isNotFound());
    }

    private function assertItemsOrderByField(array $arr, string $field, string $order = 'ASC')
    {
        $data = collect($arr)->pluck($field);

        for ($i = 1, $j = $data->count(); $i < $j; $i++) {
            $prevItem = $data[$i - 1];
            $currentItem = $data[$i];

            if ($order === 'DESC') {
                $this->assertGreaterThanOrEqual($currentItem, $prevItem);
            } else {
                $this->assertLessThanOrEqual($currentItem, $prevItem);
            }
        }
    }

    private function assertCommonProductListAndGetPagination(\Illuminate\Foundation\Testing\TestResponse $response): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {

        $response->assertViewIs('products.list');
        $response->assertViewHas([
            'category',
            'data',
        ]);

        return $response->getOriginalContent()['data'];
    }

    private function assertCommonManageProductsAndGetPagination(\Illuminate\Foundation\Testing\TestResponse $response): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {

        $response->assertViewIs('management.product.manage');
        $response->assertViewHas([
            'category',
            'data',
        ]);

        return $response->getOriginalContent()['data'];
    }
}
