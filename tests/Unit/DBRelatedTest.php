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
        $categories = Category::all()->pluck('product_type');
        $this->assertContains('3C相關', $categories);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCategoryContainsLive()
    {
        $categories = Category::all()->pluck('product_type');

        $this->assertContains('居家生活', $categories);
    }
}
