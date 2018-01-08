<?php

namespace App\Service;

use App\User;
use Illuminate\Support\Facades\Storage;

class IS_Encryption
{
    private $privateKey, $aesKey;
    private const KEY_ENC_METHOD = 'AES-256-CBC';
    private const PADDING_CHAR = 0;
    private const CA_CERT = 'app/keystore/Intermediate_CA_public.crt';
    private const CA_KEY = 'app/keystore/Intermediate_CA_private.key';

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
    public static function createKeyPair(?User $user = null)
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
        $dn = self::getDnFromUser($user);
        $csr = openssl_csr_new($dn, $private_key, array('digest_alg' => 'sha256'));

        //Sign key and get public key
        $public_key = self::signCert($csr);

        /*
        // Get public key
        $public_key = openssl_pkey_get_details($res);
        // Save the public key in public.key file. Send this file to anyone who want to send you the encrypted data.
        $public_key = $public_key["key"];
        */
        $ret = new \stdClass();
        $ret->public = $public_key;
        $ret->private = $private_key;
        return $ret;
    }

    private static function getDnFromUser(?User $user = null)
    {
        return [
            "countryName" => "TW",
            "stateOrProvinceName" => "N/A",
            "localityName" => "N/A",
            "organizationName" => "Any Buy",
            "organizationalUnitName" => "User",
            "commonName" => is_null($user) ? ('temp_key' . time() . uniqid()) : $user->account,
            "emailAddress" => is_null($user) ? 'N/A' : $user->email
        ];
    }

    private static function signCert($csr)
    {
        $caCert = 'file://' . storage_path(self::CA_CERT);
        $caKey = 'file://' . storage_path(self::CA_KEY);

        $usercert = openssl_csr_sign($csr, $caCert, $caKey, floor((2147483647 - time()) / 86400), array('digest_alg' => 'sha256'));
        openssl_x509_export($usercert, $certOut);

        return $certOut;
    }

    public static function getCaPem() :string
    {
        $content = @file_get_contents(storage_path(self::CA_CERT));
        return $content;
    }
}