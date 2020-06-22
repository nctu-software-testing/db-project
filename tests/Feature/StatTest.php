<?php

namespace Tests\Feature;

use Tests\TestCore\BaseTestCase;

class StatTest extends BaseTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testOnlyBusinessCanAccessBusinessReportView()
    {
        $this->withUser('c');
        $this->get('stat/business')->assertRedirect('/');

        $this->withUser('b');
        $this->get('stat/business')->assertViewIs('stat.business');
    }

    public function testOnlyBusinessCanAccessBusinessReportData()
    {
        $data = [
            'type' => 1,
        ];
        $this->withUser('c');
        $this->post('stat/business', $data)->assertRedirect('/');

        $this->withUser('b');
        $this->post('stat/business', $data)->assertJsonFragment([
            'success' => true,
        ]);
    }

    public function testOnlyCustomerCanAccessCustomerReportView()
    {
        $this->withUser('b');
        $this->get('stat/customer')->assertRedirect('/');

        $this->withUser('c');
        $this->get('stat/customer')->assertViewIs('stat.customer');
    }

    public function testOnlyCustomerCanAccessCustomerReportData()
    {
        $data = [
            'type' => 1,
        ];
        $this->withUser('b2');
        $this->post('stat/customer', $data)->assertRedirect('/');

        $this->withUser('c');
        $this->post('stat/customer', $data)->assertJsonFragment([
            'success' => true,
        ]);
    }

    public function testBusinessReport1()
    {
        $this->withUser('b1');
        $data = $this->post('stat/business', [
            'type' => 1,
        ])->assertJson([
            'success' => true,
            'result' => json_decode(<<<DATA
[{"category_id":"3","product_type":"女生衣著","total_count":11},{"category_id":"5","product_type":"美妝保健","total_count":14},{"category_id":"9","product_type":"居家生活","total_count":30},{"category_id":"17","product_type":"美食、伴手禮","total_count":17}]
DATA
                , true)
        ]);
    }

    public function testBusinessReport2()
    {
        $this->withUser('b2');
        $data = $this->post('stat/business', [
            'type' => 2,
            'county' => '臺北市',
        ])->assertJson([
            'success' => true,
            'result' => json_decode(<<<DATA
[{"zip_code":"104","total_count":11,"district":"中山區"},{"zip_code":"108","total_count":1,"district":"萬華區"}]
DATA
                , true)
        ]);
    }

    public function testBusinessReport3()
    {
        $this->withUser('b2');
        $data = $this->post('stat/business', [
            'type' => 3,
        ])->assertJson([
            'success' => true,
            'result' => json_decode(<<<DATA
[{"id":6,"product_name":"Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)","diff_buy":"2"},{"id":8,"product_name":"歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨","diff_buy":"2"},{"id":10,"product_name":"御茶園特上紅茶250ml/24入","diff_buy":"1"}]
DATA
                , true)
        ]);
    }

    public function testBusinessReportOthers()
    {
        $this->withUser('b2');
        $this->post('stat/business', [
            'type' => 999,
        ])->assertJson([
            'success' => false,
        ]);
    }

    public function testCustomerReport1()
    {
        $this->withUser('c');
        $data = $this->post('stat/customer', [
            'type' => 1,
        ])->assertJson([
            'success' => true,
            'result' => json_decode(<<<DATA
[{"category_id":"4","product_type":"男生衣著","total_count":1},{"category_id":"6","product_type":"手機平板與周邊","total_count":10},{"category_id":"17","product_type":"美食、伴手禮","total_count":17}]
DATA
                , true)
        ]);
    }

    public function testCustomerReport2()
    {
        $this->withUser('c');
        $data = $this->post('stat/customer', [
            'type' => 2,
        ])->assertJson([
            'success' => true,
            'result' => json_decode(<<<DATA
[{"name":"大潤發","total_count":17},{"name":"家樂福","total_count":11}]
DATA
                , true)
        ]);
    }

    public function testCustomerReportOthers()
    {
        $this->withUser('c');
        $this->post('stat/customer', [
            'type' => 999,
        ])->assertJson([
            'success' => false,
        ]);
    }
}
