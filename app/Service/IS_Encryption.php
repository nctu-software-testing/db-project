<?php

namespace App\Service;

class IS_Encryption
{
    private $privateKey, $aesKey;
    private const KEY_ENC_METHOD = 'AES-256-CBC';
    private const PADDING_CHAR = 0;

    public function __construct(string $private, string $encAesKey)
    {
        $this->privateKey = $private;
        $this->setAesKey($encAesKey);
    }

    private function setAesKey(string $encAesKey)
    {
        $aesKeyData = $this->decryptUsingRSA($encAesKey);
        $aesKeyData = json_decode($aesKeyData);
        if (!$aesKeyData || !property_exists($aesKeyData, 'iv') || !property_exists($aesKeyData, 'key'))
            throw new \Exception('Cannot Set Key');

        $this->aesKey = new \stdClass();
        $this->aesKey->key = @hex2bin($aesKeyData->key);
        $this->aesKey->iv = @hex2bin($aesKeyData->iv);

        if ($this->aesKey->key === false || $this->aesKey->iv === false)
            throw new \Exception('AES KEY error');
    }

    public function decrypt(string $hexData): string
    {
        $binData = @hex2bin($hexData);
        if ($binData === false)
            throw new \Exception('Cannot decrypt data');

        $ret = openssl_decrypt(
            $binData,
            self::KEY_ENC_METHOD,
            $this->aesKey->key,
            \OPENSSL_ZERO_PADDING | \OPENSSL_RAW_DATA,
            $this->aesKey->iv
        );

        if ($ret === false)
            throw new \Exception('Cannot decrypt data, ' . openssl_error_string());

        $paddingIdx = strlen($ret) - 1;
        while ($paddingIdx >= 0 && ord($ret[$paddingIdx]) === self::PADDING_CHAR)
            $paddingIdx--;

        $ret = substr($ret, 0, $paddingIdx + 1);

        return $ret;

    }

    private function decryptUsingRSA(string $cipher): ?string
    {
        if (is_null($this->privateKey)) {
            throw new \Exception('Private key not set corrected.');
        }
        $cipher = @base64_decode($cipher);
        if (!$cipher) {
            throw new \Exception('Cipher error');
        }
        $private_key = openssl_pkey_get_private($this->privateKey);
        // decrypt
        openssl_private_decrypt($cipher, $plain_text, $private_key);

        if (is_string($plain_text)) {
            return $plain_text;
        }

        throw new \Exception('Cipher error, ' . openssl_error_string());
    }

    /**
     * Create Key Pair
     * @property string public
     * @property string private
     */
    public static function createKeyPair()
    {
        //
        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        // Create the keypair
        $res = openssl_pkey_new($config);

        // Get private key
        openssl_pkey_export($res, $private_key);

        // Get public key
        $public_key = openssl_pkey_get_details($res);
        // Save the public key in public.key file. Send this file to anyone who want to send you the encrypted data.
        $public_key = $public_key["key"];
        $ret = new \stdClass();
        $ret->public = $public_key;
        $ret->private = $private_key;
        return $ret;
    }
}