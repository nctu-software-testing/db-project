<?php

namespace Tests\Unit;

use Tests\TestCore\BaseTestCase;

class RequestRelatedTest extends BaseTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAccessFullImageChangeCaptchaImage()
    {
        $fullImage = $this->get('/captcha/full-image');
        $currentCaptcha = session('captcha');
        $fullImage2 = $this->get('/captcha/full-image');
        $captcha2 = session('captcha');

        $this->assertNotEquals($currentCaptcha, $captcha2);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAccessMaskedImageDirect()
    {
        $maskedImage = $this->get('/captcha/masked-image');

        $maskedImage->assertStatus(403);
    }
}
