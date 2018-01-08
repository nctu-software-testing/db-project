<?php

namespace App\Http\Controllers;


use App\Service\IS_Encryption;
use Illuminate\Http\Request;

class BaseController extends \Illuminate\Routing\Controller
{
    protected $controllerName = 'base';

    protected function __construct($name = 'base')
    {
        $this->controllerName = $name;
        $this->setShareVariables('pageName', $name);
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
        if (config('app.captcha') === false) {
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

    protected function getPrivateKey(): ?string
    {
        return session('user.private_key', session('tmpKey.private'));
    }

    protected function getEncryptedData(Request $request): ?array
    {
        $encChecksum = $request->header('X-Friends-Sugoi');
        $encAesKey = $request->header('X-Friends-Tanoshii');
        if (empty($encAesKey)) return null;
        try {
            $is = new IS_Encryption($this->getPrivateKey(), $encAesKey);
            $content = $request->getContent();
            $rawData = $is->decrypt($content);
            $checksum = $is->decrypt($encChecksum);
            $hash = hash('sha256', $rawData);
            if ($hash === $checksum) {
                $ret = [];
                parse_str($rawData, $ret);

                return $ret;
            } else {
                throw new \Exception('Checksum error');
            }
        } catch (\Exception $e) {
            \Debugbar::error($e);
        }

        return null;
    }
}