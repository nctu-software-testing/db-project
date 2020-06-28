<?php

namespace Tests\Unit;

use App\Product;
use Tests\TestCore\BaseNonDBTestCase;

class ProductTest extends BaseNonDBTestCase
{
    private $model;

    protected function setUp()
    {
        parent::setUp();
        $this->model = new Product();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->model = null;
    }

    public function testState0()
    {
        $this->model->state = Product::STATE_DRAFT;
        $this->assertEquals('草稿', $this->model->GetState());
    }

    public function testState1()
    {
        $this->model->state = Product::STATE_RELEASE;
        $this->assertEquals('發佈', $this->model->GetState());
    }

    public function testState2()
    {
        $this->model->state = Product::STATE_DELETED;
        $this->assertEquals('已刪除', $this->model->GetState());
    }

    public function testStateOthers()
    {
        $this->model->state = 6354346;
        $this->assertEquals('', $this->model->GetState());
    }

    public function testOnlyDraftAllowChange0()
    {
        $this->model->state = Product::STATE_DRAFT;
        $this->assertTrue($this->model->isAllowChange());
    }

    public function testOnlyDraftAllowChange1()
    {
        $this->model->state = Product::STATE_RELEASE;
        $this->assertFalse($this->model->isAllowChange());
    }

    public function testOnlyDraftAllowChange2()
    {
        $this->model->state = Product::STATE_DELETED;
        $this->assertFalse($this->model->isAllowChange());
    }
}
