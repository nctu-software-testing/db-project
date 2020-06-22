<?php

namespace Tests\Unit;

use App\Discount;
use Tests\TestCore\BaseTestCase;

class DiscountTest extends BaseTestCase
{
    public function testSnEncoding()
    {

        $this->assertEquals('5F08-C0F0-0180-DB00', Discount::encrypt(1));
        $this->assertEquals('590F-10E0-1260-B00B', Discount::encrypt(2));
        $this->assertEquals('6208-0030-13C0-B505', Discount::encrypt(3));
    }

    public function testSnDecode()
    {
        $this->assertEquals(1, Discount::decrypt('5F08-C0F0-0180-DB00'));
        $this->assertEquals(2, Discount::decrypt('590F-10E0-1260-B00B'));
        $this->assertEquals(3, Discount::decrypt('6208-0030-13C0-B505'));
        $this->assertNull(Discount::decrypt('6208-0030-13C0-B504'));
    }

    public function testDiscountTypeA()
    {
        $model = new Discount();
        $model->type = 'A';
        $this->assertEquals('不限品項打折', $model->GetType());
    }

    public function testDiscountTypeB()
    {
        $model = new Discount();
        $model->type = 'B';
        $this->assertEquals('優惠折扣', $model->GetType());
    }

    public function testDiscountTypeC()
    {
        $model = new Discount();
        $model->type = 'C';
        $this->assertEquals('特定分類打折', $model->GetType());
    }

    public function testDiscountTypeWrongType()
    {
        $model = new Discount();
        $model->type = 'D';
        $this->assertNull($model->GetType());
    }
}
