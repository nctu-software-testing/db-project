<?php

namespace Tests\Feature;

use App\Services\CaptchaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCore\BaseTestCase;

class CaptchaTest extends BaseTestCase
{
    private const ROW_COUNT = 9;
    private const COL_COUNT = 16;
    private const GRID_SIZE = 32;
    private const CONTENT_TYPE = 'image/png';
    private const ALLOW_ERROR_OF_GRID = self::GRID_SIZE / 4;
    private const TEST_CAPTCHA_DIR = 'test_captcha';
    private const TEST_IMAGE_SIZE = [960, 540];

    protected function setUp()
    {
        parent::setUp();
        config([
            'app.captcha' => true,
        ]);

        Storage::makeDirectory(self::TEST_CAPTCHA_DIR);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    protected function assertFullImageAndGet()
    {
        $this->get('/captcha')->assertSuccessful();
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

    private function _testImageSupport(callable $imageWriter, string $ext)
    {
        [$w, $h] = self::TEST_IMAGE_SIZE;
        $allowedError = 5;
        $path = self::TEST_CAPTCHA_DIR . '/test' . $ext;

        $mock = Mockery::mock(CaptchaService::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock->shouldReceive('getImagePathRandomly')
            ->andReturn($path);

        $this->app->instance(CaptchaService::class, $mock);

        $im = imagecreatetruecolor($w, $h);
        $red = imagecolorallocate($im, 255, 0, 0);
        imagefill($im, 0, 0, $red);
        $imageWriter($im, Storage::path($path));
        imagedestroy($im);

        $fullImageResp = $this->get('/captcha/full-image');
        $im2 = imagecreatefromstring($fullImageResp->getContent());
        $color = imagecolorat($im2, 5, 5);
        $diff = abs(0xff - ($color >> 16));
        $this->assertTrue($diff < $allowedError);
        imagedestroy($im2);

        Storage::delete($path);

        Mockery::close();
    }

    public function testJpgFileSupport()
    {
        $this->_testImageSupport('imagejpeg', '.jpg');
    }

    public function testJpegFileSupport()
    {
        $this->_testImageSupport('imagejpeg', '.jpeg');
    }

    public function testPngFileSupport()
    {
        $this->_testImageSupport('imagepng', '.png');
    }

    public function testGifFileSupport()
    {
        $this->_testImageSupport('imagegif', '.gif');
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
        $captchaService = resolve(CaptchaService::class);
        $this->assertTrue(config('app.captcha'));

        $fullImageResp = $this->assertFullImageAndGet();

        $captchaValue = session('captcha');

        $sliceImageResp = $this->assertSliceImageAndGet();
        $this->assertMaskedImageAndGet();

        $verifyResp = $this->post('/captcha/verify', [
            'value' => $captchaValue['selected']['x']
        ]);

        $session = session();

        $verifyResp->assertJson([
            'result' => '驗證成功',
            'success' => true,
        ]);
        $this->assertEquals([
            'message' => '驗證成功',
            'success' => true,
        ], $captchaService->checkCaptcha($session));

        // second verify
        $this->assertEquals([
            'message' => '驗證碼錯誤',
            'success' => false,
        ], $captchaService->checkCaptcha($session));
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

    public function testTimeoutWhileVerify()
    {
        $fullImageResp = $this->assertFullImageAndGet();

        $captchaValue = session('captcha');
        $now = Carbon::now();

        $expiredTime = (clone $now)->addMinutes(3)->addSeconds(1); // 3min 1s
        Carbon::setTestNow($expiredTime);
        $verifyResp = $this->post('/captcha/verify', [
            'value' => $captchaValue['selected']['x']
        ]);

        $verifyResp->assertJson([
            'result' => '驗證碼逾時',
            'success' => false,
        ]);
    }

    public function testTimeoutAfterVerify()
    {
        $captchaService = resolve(CaptchaService::class);

        $fullImageResp = $this->assertFullImageAndGet();

        $captchaValue = session('captcha');
        $verifyResp = $this->post('/captcha/verify', [
            'value' => $captchaValue['selected']['x']
        ]);

        $verifyResp->assertJson([
            'result' => '驗證成功',
            'success' => true,
        ]);


        $now = Carbon::now();

        $expiredTime = (clone $now)->addMinutes(3)->addSeconds(1); // 3min 1s
        Carbon::setTestNow($expiredTime);

        // get verify result
        $this->assertEquals([
            'message' => '驗證碼逾時',
            'success' => false,
        ], $captchaService->checkCaptcha(session()));
    }
}
