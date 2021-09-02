<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;

trait EncryptDecryptTrait {

    public $ciphertext = '';
    public $cipher = "aes-256-cbc";

    public function encrypt($plainText, $key){
        global $cipher;
        return openssl_encrypt($plainText, $cipher, $key, $options=0, '', $tag);
    }

    function decrypt($cipherText, $key){
        global $cipher;
        return openssl_decrypt($cipherText, $cipher, $key, $options=0, '', '');
    }

}
