<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCore\BaseTestCase;

class AdminTest extends BaseTestCase
{
    private $testTargetId = 13;

    protected function setUp()
    {
        parent::setUp();
        $this->withUser('admin');
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->randomString = null;
    }

    private function _testAccessAndUserListCannotAccess()
    {
        $response = $this->get('/admin/users-manage');

        $response->assertRedirect('/');
    }

    private function _testUserPasswordDoNotChanged()
    {
        $oldPassword = User::find($this->testTargetId)->password;
        $response = $this->post('/admin/users-manage/change-password', [
            'id' => $this->testTargetId,
            'newpassword' => str_random(),
        ]);

        $response->assertRedirect('/');

        $this->assertEquals(
            $oldPassword,
            User::find($this->testTargetId)->password
        );
    }

    public function testNonAdminCannotAccess1()
    {
        $this->withUser(null);
        $this->_testAccessAndUserListCannotAccess();
    }

    public function testNonAdminCannotAccess2()
    {
        $this->withUser('b');
        $this->_testAccessAndUserListCannotAccess();
    }

    public function testNonAdminCannotAccess3()
    {
        $this->withUser('c');
        $this->_testAccessAndUserListCannotAccess();
    }

    public function testNonAdminCannotAccess4()
    {
        $this->withUser(null);
        $this->_testUserPasswordDoNotChanged();
    }

    public function testNonAdminCannotAccess5()
    {
        $this->withUser('b');
        $this->_testUserPasswordDoNotChanged();
    }

    public function testNonAdminCannotAccess6()
    {
        $this->withUser('c');
        $this->_testUserPasswordDoNotChanged();
    }

    public function testUserList()
    {
        $data = User::orderBy('id')->get();
        $response = $this->get('/admin/users-manage');

        $response->assertViewHas([
            'data' => $data,
        ]);
    }

    public function testChangeOtherUserPassword()
    {
        $oldHashedPassword = User::find($this->testTargetId)->password;
        $newPassword = str_random();
        $response = $this->post('/admin/users-manage/change-password', [
            'id' => $this->testTargetId,
            'newpassword' => $newPassword,
        ]);
        $response->assertJson([
            'result' => '修改成功。',
            'success' => true,
        ]);
        $newHashPassword = User::find($this->testTargetId)->password;
        $this->assertNotEquals($oldHashedPassword, $newHashPassword);
        Hash::check($newPassword, $newHashPassword);
    }
}
