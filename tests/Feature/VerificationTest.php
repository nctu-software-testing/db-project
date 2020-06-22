<?php

namespace Tests\Feature;

use App\User;
use App\Verification;
use Illuminate\Http\UploadedFile;
use Tests\TestCore\BaseTestCase;

class VerificationTest extends BaseTestCase
{
    private const NON_VERIFIED_USER_ACCOUNT = 'acco002';

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testAdminGetVerificationList()
    {
        $this->withUser('admin');
        $response = $this->get('verification');
        $response->assertViewIs('verification.verificationAdmin');
    }

    public function testNonAdminGetVerificationList()
    {
        $this->withUser('b');
        $response = $this->get('verification');
        $response->assertViewIs('verification.verificationUser');

        $lastVerification = Verification::where('user_id', session('user.id'))
            ->orderBy('id', 'DESC')
            ->first();

        $this->assertEquals($lastVerification->verify_result,
            $response->getOriginalContent()->data->verify_result);
    }

    public function testOnlyMyselfVerificationShown()
    {
        $this->withUser('c');
        $response = $this->get('verification');
        $response->assertViewIs('verification.verificationUser');

        $this->assertNull($response->getOriginalContent()->data);
    }

    public function testDenyVerify()
    {
        $this->submitVerifyImage(self::NON_VERIFIED_USER_ACCOUNT);
        $newVerificationId = $this->getLastVerificationId();

        $this->withUser('admin');
        $reason = str_random();
        $response = $this->post('verification', [
            'id' => $newVerificationId,
            'result' => '0',
            'reason' => $reason,
        ]);

        $newVerification = Verification::find($newVerificationId);
        $this->assertEquals('驗證失敗', $newVerification->verify_result);
        $this->assertEquals($reason, $newVerification->description);
    }

    public function testAcceptVerify()
    {
        $getCurrentEnableValue = function () {
            return User::where('account', self::NON_VERIFIED_USER_ACCOUNT)->first()->enable;
        };
        $this->assertEquals(0, $getCurrentEnableValue());
        $this->submitVerifyImage(self::NON_VERIFIED_USER_ACCOUNT);
        $newVerificationId = $this->getLastVerificationId();

        $this->withUser('admin');
        $reason = str_random();
        $response = $this->post('verification', [
            'id' => $newVerificationId,
            'result' => '1',
            'reason' => $reason,
        ]);

        $newVerification = Verification::find($newVerificationId);
        $this->assertEquals('驗證成功', $newVerification->verify_result);
        $this->assertEquals($reason, $newVerification->description);
        $this->assertEquals(1, $getCurrentEnableValue());
    }

    private function submitVerifyImage(string $account)
    {
        $this->withUser($account);
        $front = UploadedFile::fake()->image('img1.png');
        $back = UploadedFile::fake()->image('img2.png');

        $response = $this->post('new-verification', [
            'file1' => $front,
            'file2' => $back,
        ]);

        $response->assertSessionHas('log', '驗證已送出，請靜待管理員審核。');
    }

    public function testSubmitVerifyImage()
    {
        $this->submitVerifyImage(self::NON_VERIFIED_USER_ACCOUNT);
    }

    public function testSubmitWrongVerifyImage()
    {
        $this->withUser(self::NON_VERIFIED_USER_ACCOUNT);
        $front = UploadedFile::fake()->image('img1.png');

        $response = $this->post('new-verification', [
            'file1' => $front,
        ]);

        $response->assertSessionHas('log', '圖片出錯');
    }

    public function testGetVerifyImageBySelf()
    {
        $this->submitVerifyImage(self::NON_VERIFIED_USER_ACCOUNT);

        $newVerificationId = $this->getLastVerificationId();
        $this->get("verify-image/{$newVerificationId}/front")->assertHeader(
            'content-type', 'image/png');

        $this->get("verify-image/{$newVerificationId}/back")->assertHeader(
            'content-type', 'image/png');
    }

    public function testGetVerifyImageByAdmin()
    {
        $this->submitVerifyImage(self::NON_VERIFIED_USER_ACCOUNT);
        $this->withUser('admin');

        $newVerificationId = $this->getLastVerificationId();
        $this->get("verify-image/{$newVerificationId}/front")->assertHeader(
            'content-type', 'image/png');

        $this->get("verify-image/{$newVerificationId}/back")->assertHeader(
            'content-type', 'image/png');
    }

    public function testGetVerifyImageByOthers()
    {
        $this->submitVerifyImage(self::NON_VERIFIED_USER_ACCOUNT);

        $this->withUser('b');

        $newVerificationId = $this->getLastVerificationId();
        $this->get("verify-image/{$newVerificationId}/front")->assertStatus(404);
        $this->get("verify-image/{$newVerificationId}/back")->assertStatus(404);
    }

    private function getLastVerificationId()
    {
        return Verification::orderBy('id', 'DESC')->first()->id;
    }
}
