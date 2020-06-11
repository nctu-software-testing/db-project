<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use Tests\TestCore\BaseTestCase;

class CategoryTest extends BaseTestCase
{

    protected function setUp()
    {
        parent::setUp();
        $this->withUser('admin');
    }

    public function testCreateCategoryNormal()
    {
        $name = str_random();
        $response = $this->postJson('/category/manage', [
            'id' => -1,
            'product_type' => $name,
        ]);

        $response->assertJson([
            'result' => 'Ok',
            'success' => true,
        ]);
    }

    public function testCreateCategoryDuplicated()
    {
        $response = $this->postJson('/category/manage', [
            'id' => -1,
            'product_type' => '3C相關',
        ]);

        $response->assertJson([
            'result' => '已有相同名稱之類別',
            'success' => false,
        ]);
    }

    public function testCreateCategoryModifyWithSameName()
    {
        $target = '3C相關';
        $id = Category::where('product_type', $target)->first()->id;
        $response = $this->postJson('/category/manage', [
            'id' => $id,
            'product_type' => '3C相關',
        ]);

        $response->assertJson([
            'result' => 'Ok',
            'success' => true,
        ]);
    }

    public function testCreateCategoryModifyCategory()
    {
        $target = '3C相關';
        $newName = 'new_name_' . str_random();
        $id = Category::where('product_type', $target)->first()->id;
        $response = $this->postJson('/category/manage', [
            'id' => $id,
            'product_type' => $newName,
        ]);

        $response->assertJson([
            'result' => 'Ok',
            'success' => true,
        ]);

        $this->assertEquals($newName, Category::find($id)->product_type);
    }

    public function testBusinessCannotCreateCategory()
    {
        $this->withUser('b1');
        $name = str_random();
        $response = $this->postJson('/category/manage', [
            'id' => -1,
            'product_type' => $name,
        ]);

        $this->assertTrue(Category::where('product_type', $name)->doesntExist());
        $response->assertRedirect('/');
    }

    public function testCustomerCannotModifyCategory()
    {
        $this->withUser('c');
        $originalName = '3C相關';
        $newName = 'new_name_' . str_random();
        $id = Category::where('product_type', $originalName)->first()->id;
        $response = $this->postJson('/category/manage', [
            'id' => $id,
            'product_type' => $newName,
        ]);

        $response->assertRedirect('/');

        $this->assertEquals($originalName, Category::find($id)->product_type);
    }

    public function testDeleteCategory()
    {
        $name = str_random();
        $response = $this->postJson('/category/manage', [
            'id' => -1,
            'product_type' => $name,
        ]);

        $data = Category::where('product_type', $name)->first();

        $this->assertEquals($name, $data->product_type);

        $response = $this->deleteJson('/category/manage/delete', [
            'id' => $data->id,
        ]);

        $this->assertTrue(Category::where('product_type', $name)->doesntExist());
    }

    public function testDeleteCategoryWithProduct()
    {
        $product = Product::first();
        $this->assertNotEquals(null, $product);

        $response = $this->deleteJson('/category/manage/delete', [
            'id' => $product->category_id,
        ]);

        $response->assertJson([
            'result' => '不可以刪除有任何商品的分類',
            'success' => false,
        ]);
    }

    public function testGetCategory()
    {
        $this->withUser(null);

        $response = $this->get('/category');
        $data = Category::orderBy('id')->get();
        $response->assertViewHas('category', $data);
    }

    public function testGetCategoryInJson()
    {
        $this->withUser(null);

        $response = $this->get('/category?type=json');
        $data = json_decode(
            json_encode(Category::orderBy('id')->get(), JSON_UNESCAPED_UNICODE),
            true
        );

        $response->assertJson([
            'result' => $data,
            'success' => true,
        ]);
    }

    public function testManageCategoryPage()
    {
        $data = Category::orderBy('id')->get();
        $response = $this->get('category/manage');
        $response->assertViewHas('category', $data);
    }

    public function testManageCategoryWithB()
    {
        $this->withUser('b1');

        $response = $this->get('category/manage');
        $response->assertRedirect('/');
    }

    public function testManageCategoryWithC()
    {
        $this->withUser('c');

        $response = $this->get('category/manage');
        $response->assertRedirect('/');
    }
}
