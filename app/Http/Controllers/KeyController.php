<?php

namespace App\Http\Controllers;

use App\Service\IS_Encryption;
use Illuminate\Http\Request;

class KeyController extends BaseController
{

    public function __construct()
    {
        parent::__construct('test');
    }


    public function postHandShake(Request $request)
    {
        $public_key = session('user.public_key');
        if (!empty($public_key)) {
            return $this->result($public_key, true);
        } else {
            $public = session('tmpKey.public', null);
            $private = session('tmpKey.private', null);
            if ($public === null || $private === null) {
                $keyPair = IS_Encryption::createKeyPair();

                $public = $keyPair->public;
                $private = $keyPair->private;

                session()->put('tmpKey.public', $public);
                session()->put('tmpKey.private', $private);
            }

            return $this->result($public, true);
        }
    }

    /*
    public function getTest(Request $request)
    {
        return view('test');
    }

    public function postDecrypt(Request $request)
    {
        $data = $this->getEncryptedData($request);

        var_dump($data);

        return;
    }
    */
}
