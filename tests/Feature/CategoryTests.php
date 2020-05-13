<?php

namespace Tests\Feature;

use App\Category;
use Tests\TestCore\BaseTestCase;

class CategoryTests extends BaseTestCase
{

    protected function setUp()
    {
        parent::setUp();

        // TODO: mock middleware
        $this->withoutMiddleware();
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
}
