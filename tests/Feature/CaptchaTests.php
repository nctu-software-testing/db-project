<?php

namespace Tests\Feature;

use Tests\DummyController;
use Tests\TestCore\BaseTestCase;

class CaptchaTests extends BaseTestCase
{
    private const ROW_COUNT = 9;
    private const COL_COUNT = 16;
    private const GRID_SIZE = 32;
    private const CONTENT_TYPE = 'image/png';
    private const ALLOW_ERROR_OF_GRID = self::GRID_SIZE / 4;

    protected function setUp()
    {
        parent::setUp();
        config([
            'app.captcha' => true,
        ]);
    }

    protected function assertFullImageAndGet()
    {
        $fullImageResp = $this->get('/captcha/full-image');
        $fullImageResp->assertHeader('content-type', self::CONTENT_TYPE);
        $fullImageResp->assertSessionHas('captcha');

        $captchaValue = session('captcha');
        $this->assertTrue(is_array($captchaValue));
        $this->assertTrue(is_array($captchaValue['imageArray']));
        $this->assertCount(self::COL_COUNT * self::ROW_COUNT, $captchaValue['imageArray']);

        $fullImage = imagecreatefromstring($fullImageResp->getContent());
        $this->assertNotFalse($fullImage);

        $this->assertEquals(self::COL_COUNT * self::GRID_SIZE, imagesx($fullImage));
        $this->assertEquals(self::ROW_COUNT * self::GRID_SIZE, imagesy($fullImage));

        imagedestroy($fullImage);

        return $fullImageResp;
    }

    protected function assertSliceImageAndGet()
    {
        $sliceImageResp = $this->get('/captcha/slice');
        $sliceImg = imagecreatefromstring($sliceImageResp->getContent());
        $this->assertNotFalse($sliceImg);

        $this->assertEquals(1 * self::GRID_SIZE, imagesx($sliceImg));
        $this->assertEquals(self::ROW_COUNT * self::GRID_SIZE, imagesy($sliceImg));

        imagedestroy($sliceImg);

        return $sliceImageResp;
    }

    protected function assertMaskedImageAndGet()
    {
        $maskedImageResp = $this->get('/captcha/masked-image');
        $maskedImg = imagecreatefromstring($maskedImageResp->getContent());
        $this->assertNotFalse($maskedImg);

        $this->assertEquals(self::COL_COUNT * self::GRID_SIZE, imagesx($maskedImg));
        $this->assertEquals(self::ROW_COUNT * self::GRID_SIZE, imagesy($maskedImg));

        imagedestroy($maskedImg);

        return $maskedImageResp;
    }

    public function testVerifySuccess()
    {
        $fullImageResp = $this->assertFullImageAndGet();

        $captchaValue = session('captcha');

        $sliceImageResp = $this->assertSliceImageAndGet();
        $this->assertMaskedImageAndGet();

        $verifyResp = $this->post('/captcha/verify', [
            'value' => $captchaValue['selected']['x']
        ]);

        $verifyResp->assertJson([
            'result' => '驗證成功',
            'success' => true,
        ]);
    }

    public function testVerifyFail()
    {
        $fullImageResp = $this->assertFullImageAndGet();

        $captchaValue = session('captcha');

        $sliceImageResp = $this->assertSliceImageAndGet();
        $this->assertMaskedImageAndGet();

        $verifyResp = $this->post('/captcha/verify', [
            'value' => $captchaValue['selected']['x'] - self::ALLOW_ERROR_OF_GRID * rand(1, 5),
        ]);

        $verifyResp->assertJson([
            'result' => '驗證碼錯誤',
            'success' => false,
        ]);
    }

    public function testSomeErrorInVerifyAreAllowed()
    {
        for ($x = -self::ALLOW_ERROR_OF_GRID + 1; $x < self::ALLOW_ERROR_OF_GRID; $x += 4) {
            $fullImageResp = $this->assertFullImageAndGet();

            $captchaValue = session('captcha');

            $verifyResp = $this->post('/captcha/verify', [
                'value' => $captchaValue['selected']['x'] + $x,
            ]);

            $verifyResp->assertJson([
                'result' => '驗證成功',
                'success' => true,
            ]);
        }

        // test left and right part
        foreach ([
                     -self::ALLOW_ERROR_OF_GRID,
                     self::ALLOW_ERROR_OF_GRID,
                 ] as $x) {
            $fullImageResp = $this->assertFullImageAndGet();

            $captchaValue = session('captcha');

            $verifyResp = $this->post('/captcha/verify', [
                'value' => $captchaValue['selected']['x'] + $x,
            ]);

            $verifyResp->assertJson([
                'result' => '驗證碼錯誤',
                'success' => false,
            ]);
        }
    }

    public function testDoVerifyOnlyWorkOnce()
    {
        $controller = new DummyController();

        $this->assertTrue(config('app.captcha'));

        $fullImageResp = $this->assertFullImageAndGet();

        $captchaValue = session('captcha');

        $sliceImageResp = $this->assertSliceImageAndGet();
        $this->assertMaskedImageAndGet();

        $verifyResp = $this->post('/captcha/verify', [
            'value' => $captchaValue['selected']['x']
        ]);

        $verifyResp->assertJson([
            'result' => '驗證成功',
            'success' => true,
        ]);
        $this->assertEquals([
            'message' => '驗證成功',
            'success' => true,
        ], $controller->exportedCheckCaptcha());

        // second verify
        $this->assertEquals([
            'message' => '驗證碼錯誤',
            'success' => false,
        ], $controller->exportedCheckCaptcha());
    }

    public function testViewFullAgainWillChangeCaptcha()
    {
        $fullImageResp1 = $this->assertFullImageAndGet();
        $captchaValue1 = session('captcha');

        $fullImageResp2 = $this->assertFullImageAndGet();
        $captchaValue2 = session('captcha');

        $this->assertNotEquals($captchaValue1, $captchaValue2);
    }

    public function testAccessSliceImageWhileNotViewFullImage()
    {
        $sliceImageResp = $this->get('/captcha/slice');
        $this->assertTrue($sliceImageResp->isNotFound());
    }

    public function testAccessMaskedImageWhileNotViewFullImage()
    {
        $sliceImageResp = $this->get('/captcha/masked-image');
        $this->assertTrue($sliceImageResp->isForbidden());
    }
}
