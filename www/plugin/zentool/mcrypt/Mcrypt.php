<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-27
 * Time: 16:13
 */

require_once(__DIR__.'/MyCurl.php');

class Mcrypt
{
    public static $API_SECURE_KEY;
    public static $API_SECURE_IV;
    public static $PACK_FORMAT = "H*";
    public static $METHOD      = "AES-256-CBC";

    public static function _init()
    {
        // $keyInfo = json_decode(self::get_remote_key());
        $keyInfo = new stdClass;$keyInfo->key = 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3';$keyInfo->iv  = 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3';
        
        if( strlen($keyInfo->key) == 0 || strlen($keyInfo->iv) == 0 ) {
            die( $keyInfo->message . PHP_EOL . '암/복호화 키를 찾을 수 없습니다.');
        }
        self::$API_SECURE_KEY = pack(self::$PACK_FORMAT, $keyInfo->key);
        self::$API_SECURE_IV  = pack(self::$PACK_FORMAT, $keyInfo->iv );
    }

    public static function get_remote_key()
    {
        $url = "http://keygen.itty.kr/keystore/".$_SERVER['SERVER_NAME'];
        $curl= new MyCurl($url);
        $curl->createCurl();
        return $curl;
    }

    public static function jumin_encrypt($plaintext)
    {
        return static::encrypt($plaintext);
    }

    public static function jumin_decrypt($ciphertext_base64)
    {
        return static::decrypt($ciphertext_base64);
    }
    public static function encrypt($plaintext)
    {
//        if( self::mayBeEncrypted( $plaintext )) return $plaintext;

        // if( self::decrypt($plaintext) != "" ) return $plaintext;

        $key= hash('sha256', self::$API_SECURE_KEY);
        $iv = substr(hash('sha256', self::$API_SECURE_IV), 0, 32);

        $encrypted = str_replace("=", "", base64_encode( openssl_encrypt($plaintext, self::$METHOD, $key, 0, $iv)) );

        if( strlen($encrypted) == 0 ) return $plaintext;
        return $encrypted;
    }

    public static function decrypt($ciphertext_base64)
    {
//        if( self::mayBeDecrypted($ciphertext_base64)) return $ciphertext_base64;

        $key = hash('sha256', self::$API_SECURE_KEY);
        $iv = substr(hash('sha256', self::$API_SECURE_IV), 0, 32);

        $decrypted = openssl_decrypt( base64_decode($ciphertext_base64), self::$METHOD, $key, 0, $iv );

        if( strlen($decrypted) == 0 ) return $ciphertext_base64;
        return $decrypted;
    }

    public static function zen_encrypt($plaintext)
    {
        return static::encrypt($plaintext);
    }

    public static function zen_decrypt($ciphertext_base64)
    {
        return static::decrypt($ciphertext_base64);
    }

    private static function mayBeEncrypted($plaintext)
    {
        return false;
    }


    private static function mayBeDecrypted($plaintext)
    {
        return false;
    }

}


function encrypted_admin(){
    global $encrypted_admin;
    return $encrypted_admin;
}

Mcrypt::_init();
$encrypted_admin = 'admin';
$encrypted_k2k9  = 'k2k9';
