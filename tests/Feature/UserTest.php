<?php

namespace Tests\Feature;

use App\Services\CaptchaService;
use Mockery;
use Tests\TestCore\BaseTestCase;

class UserTest extends BaseTestCase
{

    protected function setUp()
    {
        parent::setUp();
        config([
            'app.captcha' => true,
        ]);
    }

    public function testRegisterBusiness()
    {
        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $account = str_random();
        $ret = $this->post('/register', [
            'account' => $account,
            'password' => $account . '_pwd',
            'name' => $account . '_name',
            'sn' => $account . '_sn',
            'email' => $account . '@mail.com',
            'birthday' => date('Y-m-d', mt_rand(0, time())),
            'gender' => '男',
            'role' => 'B',
        ]);

        $ret->assertSuccessful();

        Mockery::close();
    }
}
