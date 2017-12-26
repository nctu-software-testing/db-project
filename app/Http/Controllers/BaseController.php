<?php

namespace App\Http\Controllers;

class BaseController extends \Illuminate\Routing\Controller
{
    protected $controllerName = 'base';
    protected $captcha = false;

    protected function __construct($name = 'base')
    {
        $this->captcha = env('APP_CAPTCHA', false);
        $this->controllerName = $name;
        $this->setShareVariables('pageName', $name);
        $this->setShareVariables('captcha', $this->captcha);

    }

    protected function setShareVariables($key, $val)
    {
        view()->share($key, $val);
        return $this;
    }

    protected function result($data, $success = true)
    {
        return response()->json([
            'result' => $data,
            'success' => $success,
        ]);
    }

    protected function setMessage($message, $type = 'info')
    {
        return session()->flash('log', [
            'message' => $message,
            'type' => $type
        ]);
    }

    protected function checkCaptcha(): array
    {
        if (env('APP_CAPTCHA', false) === false) {
            return ['success' => true, 'message' => ''];
        }
        $captcha = session('captcha');

        if (!$captcha || $captcha['passed'] !== true) {
            return ['success' => false, 'message' => '驗證碼錯誤'];
        }
        session()->forget('captcha');

        if (time() - $captcha['passed_at'] > CaptchaController::TIMEOUT) {
            return ['success' => false, 'message' => '驗證碼逾時'];
        } else {
            return ['success' => true, 'message' => '驗證成功'];
        }
    }
}