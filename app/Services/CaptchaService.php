<?php


namespace App\Services;


use App\Http\Controllers\CaptchaController;
use Carbon\Carbon;

class CaptchaService
{
    private const TIMEOUT = 3 * 60;

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
}