<?php

namespace Tests\Feature;

use App\Services\CaptchaService;
use App\User;
use Mockery;
use Tests\TestCore\BaseTestCase;

class UserTest extends BaseTestCase
{
    /**
     * @var string|null
     */
    private $randomString;

    protected function setUp()
    {
        parent::setUp();
        config([
            'app.captcha' => true,
        ]);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->randomString = null;
    }

    public function testRegisterBusiness()
    {
        $this->get('/register')->assertSuccessful();

        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/register', array_merge($this->getBasicRegUserInfo(), [
            'gender' => '男',
            'role' => 'B',
        ]));

        $ret->assertSuccessful();
        $ret->assertJson([
            'success' => true,
            'result' => '註冊成功',
        ]);

        Mockery::close();
    }

    public function testRegisterCustomer()
    {
        $this->get('/register')->assertSuccessful();

        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/register', array_merge($this->getBasicRegUserInfo(), [
            'gender' => '女',
            'role' => 'C',
        ]));

        $ret->assertSuccessful();
        $ret->assertJson([
            'success' => true,
            'result' => '註冊成功',
        ]);


        Mockery::close();
    }

    public function testWrongUserRole()
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
            'gender' => '',
            'role' => 'A',
        ]);

        $ret->assertJson([
            'result' => '參數錯誤',
            'success' => false,
        ]);

        Mockery::close();
    }

    public function testWrongUserGender()
    {
        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/register', array_merge($this->getBasicRegUserInfo(), [
            'gender' => '測',
            'role' => 'B',
        ]));

        $ret->assertJson([
            'result' => '參數錯誤',
            'success' => false,
        ]);

        Mockery::close();
    }

    public function testWrongUserMail()
    {
        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/register', array_merge($this->getBasicRegUserInfo(), [
            'email' => str_random(),
        ]));

        $ret->assertJson([
            'success' => false,
            'result' => '參數錯誤',
        ]);

        Mockery::close();
    }

    public function testDuplicatedUserAccount()
    {
        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/register', array_merge($this->getBasicRegUserInfo(), [
            'account' => 'admin',
        ]));

        $ret->assertJson([
            'success' => false,
            'result' => '已有相同帳戶',
        ]);

        Mockery::close();
    }

    private function getBasicRegUserInfo()
    {
        $this->randomString = $str = str_random();
        return [
            'account' => $str,
            'password' => $str . '_pwd',
            'name' => $str . '_name',
            'sn' => $str . '_sn',
            'email' => $str . '@mail.com',
            'birthday' => date('Y-m-d', mt_rand(0, time())),
            'gender' => '男',
            'role' => 'B',
        ];
    }

    public function testLoginSuccessful()
    {
        $this->testRegisterBusiness();

        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/login', [
            'account' => $this->randomString,
            'password' => $this->randomString . '_pwd',
        ]);

        $ret->assertJson([
            'success' => true,
            'result' => '登入成功',
        ]);

        Mockery::close();
    }

    public function testLoginWithoutCaptcha()
    {
        $this->testRegisterBusiness();

        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => false, 'message' => 'Should Failed'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/login', [
            'account' => $this->randomString,
            'password' => $this->randomString . '_pwd',
        ]);

        $ret->assertJson([
            'success' => false,
            'result' => 'Should Failed',
        ]);

        Mockery::close();
    }

    public function testLoginWithWrongPassword()
    {
        $this->testRegisterBusiness();

        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/login', [
            'account' => $this->randomString,
            'password' => $this->randomString . '_pwd_',
        ]);

        $ret->assertJson([
            'success' => false,
            'result' => '登入失敗',
        ]);

        Mockery::close();
    }

    public function testLoginWithNonExists()
    {
        $this->testRegisterBusiness();

        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING'])
            ->times(1);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/login', [
            'account' => str_random(),
            'password' => $this->randomString . '_pwd',
        ]);

        $ret->assertJson([
            'success' => false,
            'result' => '登入失敗',
        ]);

        Mockery::close();
    }

    public function testGetUserInfoWithLogin()
    {
        $this->testLoginSuccessful();
        $user = User::find(session('user.id'));
        $this->assertNotNull($user);
        $this->get('profile')->assertViewHas('data', $user);
    }

    public function testGetUserInfoWithoutLogin()
    {
        $user = User::find(session('user.id'));
        $this->assertNull($user);
        $this->get('profile')->assertRedirect('/');
    }

    public function testLogout()
    {
        $this->testLoginSuccessful();
        $user = User::find(session('user.id'));
        $this->assertNotNull($user);
        $this->post('/logout');

        $user = User::find(session('user.id'));
        $this->assertNull($user);
    }

    public function testTryToAccessDeletedUserProfile()
    {
        $this->testLoginSuccessful();
        $user = User::find(session('user.id'));
        $this->assertNotNull($user);
        $this->get('profile')->assertViewHas('data', $user);

        $user->delete();
        $this->get('profile')->assertRedirect('/');
    }

    public function testClearAvatar()
    {
        $this->testLoginSuccessful();

        $this->post('profile/avatar', [
            'avatar' => '',
        ])->assertJson([
            'success' => true,
            'result' => [
                'url' => asset('images/avatar.svg'),
            ],
        ]);

        $user = User::find(session('user.id'));
        $this->assertEquals('', $user->avatar);
    }

    public function testSetAvatar()
    {
        $this->testLoginSuccessful();

        $imageId = str_random(6);

        $ret = $this->post('profile/avatar', [
            'avatar' => $imageId,
        ]);
        $ret->assertSuccessful();
        $response = $ret->json();
        $this->assertEquals(true, $response['success']);

        $responseUrl = $response['result']['url'];
        $this->assertTrue(is_string($responseUrl));
        $this->assertTrue(strpos($responseUrl, 'https://i.imgur.com/') === 0);
        $this->assertTrue(strpos($responseUrl, $imageId) !== false);

        $user = User::find(session('user.id'));
        $this->assertEquals($imageId, $user->avatar);
    }

    public function testChangePasswordSuccessful()
    {
        $this->testLoginSuccessful();

        $newPassword = str_random(6);

        $ret = $this->post('change-password', [
            'oldpassword' => $this->randomString . '_pwd',
            'newpassword' => $newPassword,
        ]);
        $ret->assertSuccessful();
        $ret->assertJson([
            'result' => '修改成功，請重新登入。',
            'success' => true,
        ]);

        $ret = $this->post('/login', [
            'account' => $this->randomString,
            'password' => $newPassword,
        ]);

        $ret->assertJsonFragment([
            'success' => true,
        ]);
    }

    public function testChangePasswordWithWrongPassword()
    {
        $this->testLoginSuccessful();

        $newPassword = str_random();

        $ret = $this->post('change-password', [
            'oldpassword' => str_random(),
            'newpassword' => $newPassword,
        ]);
        $ret->assertSuccessful();
        $ret->assertJson([
            'result' => '密碼不符合',
            'success' => false,
        ]);

        $this->post('/logout');

        $ret = $this->post('/login', [
            'account' => $this->randomString,
            'password' => $newPassword,
        ]);

        $ret->assertJsonFragment([
            'success' => false,
        ]);

        $ret = $this->post('/login', [
            'account' => $this->randomString,
            'password' => $this->randomString . '_pwd',
        ]);

        $ret->assertJsonFragment([
            'success' => true,
        ]);
    }

    public function testChangeEmailSuccessful()
    {
        $this->testLoginSuccessful();

        $newMail = str_random() . '@mail.com';

        $ret = $this->post('change-email', [
            'email' => $newMail,
        ]);
        $ret->assertSuccessful();
        $ret->assertJsonFragment([
            'success' => true,
        ]);

        $user = User::find(session('user.id'));
        $this->assertNotNull($user);
        $this->assertEquals($newMail, $user->email);
    }

    public function testChangeEmailWithWrongFormat()
    {
        $this->testLoginSuccessful();

        $newMail = str_random() . 'hjohj';

        $ret = $this->post('change-email', [
            'email' => $newMail,
        ]);

        $user = User::find(session('user.id'));
        $this->assertNotNull($user);

        // check mail do not updated
        $this->assertEquals($this->randomString . '@mail.com', $user->email);

        $ret->assertJsonFragment([
            'success' => false,
        ]);
    }

    public function testLoggedInUserCannotLoginAgain()
    {
        $this->testLoginSuccessful();
        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('checkCaptcha')
            ->andReturn(['success' => true, 'message' => 'TESTING']);

        $this->app->instance(CaptchaService::class, $mock);

        $ret = $this->post('/login', [
            'account' => 'admin',
            'password' => 'admin',
        ]);

        $ret->assertRedirect('/');
        $this->assertNotEquals(User::ADMIN_ROLE, session('user.role'));

        Mockery::close();
    }
}
