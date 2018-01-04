<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends BaseController
{


    public function __construct()
    {
        parent::__construct('test');
    }


    public function postHandShake(Request $request)
    {
        $public_key = $request->session()->get('user')->public_key;
        if (!empty($public_key))
            return $this->result($public_key, true);
        else
            return $this->result('Error', false);
    }

    public function getTest(Request $request)
    {
        return view('test');
    }

    public function postDecrypt(Request $request)
    {
        $cipher_text = request('cipher_text');
        $cipher = base64_decode($cipher_text);
        $private_key_text = $request->session()->get('user')->private_key;
        $private_key = openssl_pkey_get_private($private_key_text);

        // decrypt
        openssl_private_decrypt($cipher, $plain_text, $private_key);

        if (is_null($plain_text)) {
            return $this->result('Something went wrong.', false);
        }

        return $this->result($plain_text, true);
    }
}
