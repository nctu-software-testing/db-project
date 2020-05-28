<?php


namespace App\Services;


use App\Http\Controllers\CaptchaController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CaptchaService
{
    private const TIMEOUT = 3 * 60;
    private const PATH = 'captcha';
    private $imageList = null;

    public function checkCaptcha(\Illuminate\Session\SessionManager $session): array
    {
        if (config('app.captcha') === false) {
            return ['success' => true, 'message' => ''];
        }
        $captcha = $session->get('captcha');

        if (!$captcha || $captcha['passed'] !== true) {
            return ['success' => false, 'message' => '驗證碼錯誤'];
        }
        $session->forget('captcha');

        $now = Carbon::now();
        if ($now->timestamp - $captcha['passed_at'] > CaptchaController::TIMEOUT) {
            return ['success' => false, 'message' => '驗證碼逾時'];
        } else {
            return ['success' => true, 'message' => '驗證成功'];
        }
    }

    public function isTimeout(int $captchaTime): bool
    {
        $now = Carbon::now()->timestamp;

        return $now - $captchaTime > CaptchaController::TIMEOUT;
    }

    public function getImagePathRandomly(): string
    {
        self::getImageList();
        $index = array_rand($this->imageList);
        return $this->imageList[$index];
    }

    private function getImageList()
    {
        if ($this->imageList != null) return;
        $imgList = Storage::files(self::PATH);
        $this->imageList = $imgList;
    }
}
