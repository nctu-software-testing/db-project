<?php

namespace Tests\Feature;

use App\Location;
use Tests\TestCore\BaseTestCase;

class LocationTest extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->withUser('c');
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGetLocations()
    {
        $data = Location::where('user_id', session('user.id'))
            ->get();
        $response = $this->get('/location');
        $viewModel = $response->getOriginalContent()->data;
        $this->assertEquals($data, $viewModel->getCollection());
    }

    public function testAddLocation()
    {
        $lastLocationId = Location::orderBy('id', 'DESC')->first()->id;

        $addr = '大學路1001號';
        $zip = '30010';
        $response = $this->post('/location', [
            'address' => $addr,
            'zipcode' => $zip,
        ]);
        $response->assertSessionHas('log', '建立成功');

        $newLocation = Location::where('id', '>', $lastLocationId)->first();
        $this->assertEquals($addr, $newLocation->address);
        $this->assertEquals($zip, $newLocation->zip_code);
        $this->assertEquals(session('user.id'), $newLocation->user_id);
    }
}
