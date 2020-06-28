<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use App\ProductPicture;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function testGetProductDetails()
    {
        $productId = 59;
        $response = $this->get("/products/item/{$productId}");
        $response->assertViewIs('products.item');

        $data = $response->getOriginalContent();
        $this->assertEquals($productId, $data['p']->id);
        $this->assertEquals(7, $data['count']);
    }

    public function testGetExpiredProductDetails()
    {
        Carbon::setTestNow('2000-01-02 03:04:05');
        $productId = 59;
        $response = $this->get("/products/item/{$productId}");
        $this->assertTrue($response->isNotFound() || $response->isForbidden());
    }

    public function testSelfAllowViewDraftProduct()
    {
        $productId = 59;
        Product::where('id', $productId)->update(['state' => Product::STATE_DRAFT]);
        $this->withUser('b1');
        $response = $this->get("/products/item/{$productId}");
        $response->assertViewIs('products.item');
    }

    public function testOthersCannotViewDraftProduct()
    {
        $productId = 59;
        Product::where('id', $productId)->update(['state' => Product::STATE_DRAFT]);
        $this->withUser('b2');
        $response = $this->get("/products/item/{$productId}");
        $this->assertTrue($response->isNotFound() || $response->isForbidden());
    }

    public function testGetDefaultProductImage()
    {
        $response = $this->get('/products/item-image/22/0');
        $response->assertHeader('content-type', 'image/png');
        $image = $response->getContent();
        $defaultImage = Storage::get('public/product-no-image.png');

        $this->assertTrue($defaultImage === $image);
    }

    public function testGetProductImage()
    {
        $pid = 1;
        Storage::fake();

        foreach (range(1, 2) as $sort) {
            $fakeImage = UploadedFile::fake()->image('photo' . $sort . '.jpg', ($sort + 1), ($sort + 2));
            $path = $fakeImage->store('images');
            ProductPicture::where('product_id', $pid)->where('sort', $sort)->update([
                'path' => $path,
            ]);

            $response = $this->get('/products/item-image/1/' . $sort);
            $this->assertEquals($fakeImage->getSize(), strlen($response->getContent()));
        }
    }

    public function testDeleteProduct()
    {
        $productId = 66;

        $this->withUser(null);
        $this->get("/products/item/{$productId}")->assertSuccessful();

        $this->withUser('b2');
        $this->post('/products/deleteProduct', [
            'id' => $productId
        ])->assertJson([
            'success' => true,
            'result' => 'ok',
        ]);

        $this->withUser(null);
        $itemResp = $this->get("/products/item/{$productId}");
        $this->assertTrue($itemResp->isNotFound() || $itemResp->isForbidden());
    }

    public function testDeleteOthersProduct()
    {
        $productId = 66; // owner is b2

        $this->withUser(null);
        $this->get("/products/item/{$productId}")->assertSuccessful();

        $this->withUser('b1');
        $this->post('/products/deleteProduct', [
            'id' => $productId
        ]);

        $this->withUser(null);
        $this->get("/products/item/{$productId}")->assertSuccessful();
    }

    public function testPublishProduct()
    {
        $productId = 58;

        $this->withUser(null);
        $itemResp = $this->get("/products/item/{$productId}");
        $this->assertTrue($itemResp->isNotFound() || $itemResp->isForbidden());

        $this->withUser('b');
        $this->post('products/manage/release-product', [
            'id' => $productId
        ])->assertJson([
            'success' => true,
            'result' => 'ok',
        ]);

        $this->withUser(null);
        $this->get("/products/item/{$productId}")->assertSuccessful();
    }

    public function testPublishOthersProduct()
    {
        $productId = 58;

        $this->withUser(null);
        $itemResp = $this->get("/products/item/{$productId}");
        $this->assertTrue($itemResp->isNotFound() || $itemResp->isForbidden());

        $this->withUser('b2');
        $this->post('products/manage/release-product', [
            'id' => $productId
        ])->assertJson([
            'success' => false,
        ]);

        $this->withUser(null);
        $itemResp = $this->get("/products/item/{$productId}");
        $this->assertTrue($itemResp->isNotFound() || $itemResp->isForbidden());
    }

    public function testAddProduct()
    {
        $this->withUser('b');
        $lastPid = Product::orderBy('id', 'DESC')->first()->id;
        $title = 'new_product_' . str_random();
        $desc = str_random(40);
        $category = 5;
        $fakeImg1 = UploadedFile::fake()->image('img1.png');
        $fakeImg2 = UploadedFile::fake()->image('img2.png');

        $response = $this->post('products/item/manage', [
            'Edit_id' => '0',
            'title' => $title,
            'category' => $category,
            'price' => 567,
            'start_date' => '2020-01-02 03:04:05',
            'end_date' => '2022-06-07 08:09:10',
            'amount' => 9876,
            'info' => $desc,
            'productImage' => [
                $fakeImg1,
                $fakeImg2,
            ],
        ]);

        $response->assertSessionHas('log', '建立成功');
        $newProduct = Product::find($lastPid + 1);
        $this->assertEquals($title, $newProduct->product_name);
        $this->assertEquals($desc, $newProduct->product_information);
        $this->assertEquals('2020-01-02 03:04:05', $newProduct->start_date);
        $this->assertEquals('2022-06-07 08:09:10', $newProduct->end_date);
        $this->assertEquals(567, $newProduct->price);
        $this->assertEquals($category, $newProduct->category_id);
        $this->assertEquals(session('user.id'), $newProduct->user_id);
        $this->assertEquals(9876, $newProduct->amount);

        $imagesCnt = ProductPicture::where('product_id', $newProduct->id)->count();
        $this->assertEquals(2, $imagesCnt);
    }

    public function testEditProduct()
    {
        $targetPid = 59;
        Product::where('id', $targetPid)->update([
            'state' => Product::STATE_DRAFT,
        ]);
        $this->withUser('b1');
        $title = 'new_product_' . str_random();
        $desc = str_random(40);
        $category = 5;
        $fakeImg1 = UploadedFile::fake()->image('img1.png');
        $fakeImg2 = UploadedFile::fake()->image('img2.png');
        $fakeImg3 = UploadedFile::fake()->image('img3.png');

        $originImageLen = ProductPicture::where('product_id', $targetPid)->count();
        $deleteImage = array_fill(0, $originImageLen, '1');
        unset($deleteImage[1]); // keep 2nd image
        $response = $this->post('products/item/manage', [
            'Edit_id' => $targetPid,
            'title' => $title,
            'category' => $category,
            'price' => 567,
            'start_date' => '2020-01-02 03:04:05',
            'end_date' => '2022-06-07 08:09:10',
            'amount' => 9876,
            'info' => $desc,
            'productImage' => [
                $fakeImg1,
                $fakeImg2,
                $fakeImg3,
            ],
            'delImage' => $deleteImage,
        ]);

        $response->assertSessionHas('log', '修改成功');
        $product = Product::find($targetPid);
        $this->assertEquals($title, $product->product_name);
        $this->assertEquals($desc, $product->product_information);
        $this->assertEquals('2020-01-02 03:04:05', $product->start_date);
        $this->assertEquals('2022-06-07 08:09:10', $product->end_date);
        $this->assertEquals(567, $product->price);
        $this->assertEquals($category, $product->category_id);
        $this->assertEquals(session('user.id'), $product->user_id);
        $this->assertEquals(9876, $product->amount);

        $imagesCnt = ProductPicture::where('product_id', $product->id)->count();
        $this->assertEquals(3 + 1 /* skipped 2nd image */, $imagesCnt);
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
