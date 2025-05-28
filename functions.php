<?php
function encryptPassword($password, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptPassword($encrypted, $key) {
    $data = base64_decode($encrypted);
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($data, 0, $iv_length);
    $encrypted_password = substr($data, $iv_length);
    return openssl_decrypt($encrypted_password, 'aes-256-cbc', $key, 0, $iv);
}