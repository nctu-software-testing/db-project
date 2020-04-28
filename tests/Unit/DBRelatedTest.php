<?php

namespace Tests\Unit;

use App\Category;
use Tests\TestCore\BaseTestCase;

class DBRelatedTest extends BaseTestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCategoryContains3C()
    {
        $categories = Category::all();
        $categoryArray = [];
        foreach($categories as $category) {
            $categoryArray[$category->product_type] = true;
        }

        $this->assertArrayHasKey('3C相關', $categoryArray);
    }
}
