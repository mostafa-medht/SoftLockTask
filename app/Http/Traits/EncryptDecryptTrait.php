<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;

trait EncryptDecryptTrait {

    public $ciphertext = '';
    public $cipher = "aes-256-cbc";

    public function encrypt($data, $key){
        global $cipher;
        return openssl_encrypt($data, $cipher, $key, $options=0, '', $tag);
    }

    function decrypt($data, $key){
        global $cipher;
        return openssl_decrypt($data, $cipher, $key, $options=0, '', '');
    }

}

