<?php


namespace Tests\TestCore;

use Tests\TestCase;

abstract class BaseNonDBTestCase extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

}