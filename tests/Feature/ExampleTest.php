<?php

namespace Tests\Feature;

use Tests\TestCore\BaseTestCase;

class ExampleTest extends BaseTestCase
{
    //use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
