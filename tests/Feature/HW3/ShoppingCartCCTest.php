<?php


namespace Tests\Feature\HW3;

use Tests\TestCore\BaseTestCase;

class ShoppingCartCCTest extends BaseTestCase
{
    use ShoppingCartTestsPart1;
    use ShoppingCartTestsPart2;

    protected function setUp()
    {
        parent::setUp();
        $this->withUser('c');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
