<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestController extends BaseController
{


    public function __construct()
    {
        parent::__construct('test');
    }


    public function postPublicKey(Request $request){
        $path = 'public/IS/public.key';
        $result =  file_get_contents($path);
        return $result;
    }

    public function getTest(Request $request)
    {
        $puk = $request->session()->get('user')->public_key;
        return view('test', ['puk' => $puk]);
    }

    public function decrypt(Request $request)
    {
        $cipher_text = request('cipher_text');
        $private_key = $request->session()->get('user')->private_key;

        // decrypt
        openssl_private_decrypt($cipher_text, $plain_text, $private_key);

        return $this->result($plain_text, true);
    }
}
